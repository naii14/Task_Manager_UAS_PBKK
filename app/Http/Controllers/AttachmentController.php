<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\FileVersion;
use App\Jobs\GenerateImageThumbnail;
use App\Jobs\SimulateVirusScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use OpenApi\Attributes as OA;

class AttachmentController extends Controller
{
    #[OA\Post(
        path: "/tasks/{taskId}/attachments",
        summary: "Upload Attachment",
        description: "Unggah file lampiran untuk tugas. Mendukung standard upload (file <= 50MB) dan chunked upload (file > 50MB).",
        tags: ["Attachments"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(
        response: 201,
        description: "File berhasil diunggah"
    )]
    public function store(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);

        // Check if it is a chunked upload
        if ($request->has('uuid') && $request->has('chunk_index')) {
            return $this->handleChunkedUpload($request, $task);
        }

        // Standard single/multiple file upload validation
        $request->validate([
            'file' => 'required|file|max:51200', // 50MB max for standard uploads
        ]);

        $file = $request->file('file');
        
        $attachment = $this->saveFileAndCreateAttachment($file, $task);

        return response()->json([
            'status' => 'success',
            'message' => 'File berhasil diunggah',
            'data' => $attachment
        ], 201);
    }

    /**
     * Handle chunked upload for large files (>50MB).
     */
    private function handleChunkedUpload(Request $request, Task $task)
    {
        $request->validate([
            'uuid' => 'required|string',
            'chunk_index' => 'required|integer',
            'total_chunks' => 'required|integer',
            'file_name' => 'required|string',
            'file' => 'required|file',
        ]);

        $uuid = $request->uuid;
        $chunkIndex = $request->chunk_index;
        $totalChunks = $request->total_chunks;
        $fileName = $request->file_name;
        $chunkFile = $request->file('file');

        // Store chunk in temp directory
        $tempPath = "chunks/{$uuid}";
        $chunkFile->storeAs($tempPath, $chunkIndex, 'local');

        // Check if all chunks are uploaded
        $uploadedChunks = Storage::disk('local')->files($tempPath);

        if (count($uploadedChunks) === (int)$totalChunks) {
            // Merge chunks
            Storage::disk('public')->makeDirectory('attachments');
            $finalFileName = time() . '_' . $fileName;
            $finalPath = 'attachments/' . $finalFileName;
            $absoluteFinalPath = Storage::disk('public')->path($finalPath);

            $out = fopen($absoluteFinalPath, 'wb');
            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkPath = Storage::disk('local')->path("{$tempPath}/{$i}");
                $in = fopen($chunkPath, 'rb');
                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }
                fclose($in);
            }
            fclose($out);

            // Clean up temporary chunks
            Storage::disk('local')->deleteDirectory($tempPath);

            // Create or update task attachment with versioning
            $mimeType = Storage::disk('public')->mimeType($finalPath) ?? 'application/octet-stream';
            $fileSize = Storage::disk('public')->size($finalPath);

            $attachment = $this->createOrUpdateAttachment($task, $fileName, $finalPath, $fileSize, $mimeType);

            return response()->json([
                'status' => 'success',
                'message' => 'Upload besar (chunked) selesai dan digabungkan',
                'data' => $attachment
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Chunk {$chunkIndex} berhasil diunggah."
        ]);
    }

    /**
     * Helper to process the final file and apply versioning logic.
     */
    private function saveFileAndCreateAttachment($file, $task)
    {
        $fileName = $file->getClientOriginalName();
        $storedName = time() . '_' . $fileName;
        $filePath = $file->storeAs('attachments', $storedName, 'public');
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        return $this->createOrUpdateAttachment($task, $fileName, $filePath, $fileSize, $mimeType);
    }

    /**
     * Core database logic for creating/updating attachment (Versioning support).
     */
    private function createOrUpdateAttachment(Task $task, $fileName, $filePath, $fileSize, $mimeType)
    {
        // 1. Versioning: Check if an attachment with the same name already exists in this task
        $existing = TaskAttachment::where('task_id', $task->id)
            ->where('file_name', $fileName)
            ->first();

        if ($existing) {
            // Get count of existing versions to calculate next version number
            $versionCount = FileVersion::where('attachment_id', $existing->id)->count();
            $nextVersionNumber = $versionCount + 1;

            // Move the current active attachment info to file_versions table
            FileVersion::create([
                'attachment_id' => $existing->id,
                'version' => $nextVersionNumber,
                'file_name' => $existing->file_name,
                'file_path' => $existing->file_path,
                'file_size' => $existing->file_size,
                'mime_type' => $existing->mime_type,
            ]);

            // Update main attachment with new uploaded file details
            $existing->update([
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'uploaded_at' => now(),
            ]);

            $attachment = $existing;
            Log::info("Attachment versioning: Dibuat versi {$nextVersionNumber} untuk file {$fileName}");
        } else {
            // No existing file with this name, create standard record
            $attachment = TaskAttachment::create([
                'task_id' => $task->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'uploaded_at' => now(),
            ]);
        }

        // Trigger SSE reload
        Cache::forget("task_detail_{$task->id}");
        Cache::put('tasks_last_updated', microtime(true), 600);

        // 2. Dispatch Background Jobs
        // Thumbnail generation for images
        if (str_starts_with($mimeType, 'image/')) {
            GenerateImageThumbnail::dispatch($attachment->id);
        }

        // Virus scanning simulation
        SimulateVirusScan::dispatch($attachment->id);

        return $attachment;
    }

    #[OA\Get(
        path: "/attachments/{id}/download",
        summary: "Download / Stream Attachment",
        description: "Unduh berkas lampiran secara langsung. Jika berkas berupa video (e.g. MP4/WebM), mendukung pemutaran range-requests (206 Partial Content) untuk seek player.",
        tags: ["Attachments"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(
        response: 200,
        description: "Download berkas / Ranged stream partial content"
    )]
    public function download($id)
    {
        $attachment = TaskAttachment::findOrFail($id);
        
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Berkas fisik tidak ditemukan.'
            ], 404);
        }

        $absolutePath = Storage::disk('public')->path($attachment->file_path);

        // Advanced Challenge: Handle video streaming with byte range-requests
        if (str_starts_with($attachment->mime_type, 'video/')) {
            return $this->streamVideo($absolutePath, $attachment->mime_type);
        }

        return response()->download($absolutePath, $attachment->file_name);
    }

    /**
     * Custom ranged streaming method for smooth HTML5 video scrubbing.
     */
    private function streamVideo($path, $mimeType)
    {
        $size = filesize($path);
        $length = $size;
        $start = 0;
        $end = $size - 1;

        $headers = [
            'Content-Type' => $mimeType,
            'Accept-Ranges' => 'bytes',
        ];

        if (isset($_SERVER['HTTP_RANGE'])) {
            $c_start = $start;
            $c_end = $end;

            list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
            if (strpos($range, ',') !== false) {
                return response()->json(['error' => 'HTTP_RANGE not supported'], 416);
            }
            if ($range == '-') {
                $c_start = $size - substr($range, 1);
            } else {
                $range = explode('-', $range);
                $c_start = $range[0];
                $c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
            }
            $c_end = ($c_end > $end) ? $end : $c_end;
            if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                return response()->json(['error' => 'Range Not Satisfiable'], 416);
            }
            $start = $c_start;
            $end = $c_end;
            $length = $end - $start + 1;
            
            $headers['Content-Range'] = "bytes {$start}-{$end}/{$size}";
            $status = 206;
        } else {
            $status = 200;
        }

        $headers['Content-Length'] = $length;

        $stream = fopen($path, 'rb');
        fseek($stream, $start);

        return response()->stream(function() use ($stream, $length) {
            $buffer = 1024 * 8;
            $bytesSent = 0;
            while (!feof($stream) && $bytesSent < $length) {
                $toRead = min($buffer, $length - $bytesSent);
                echo fread($stream, $toRead);
                ob_flush();
                flush();
                $bytesSent += $toRead;
            }
            fclose($stream);
        }, $status, $headers);
    }

    #[OA\Delete(
        path: "/attachments/{id}",
        summary: "Delete Attachment",
        description: "Hapus berkas lampiran beserta seluruh riwayat versi fisiknya.",
        tags: ["Attachments"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(
        response: 200,
        description: "Attachment berhasil dihapus"
    )]
    public function destroy($id)
    {
        $attachment = TaskAttachment::findOrFail($id);

        // Delete all physical version files
        foreach ($attachment->versions as $version) {
            if (Storage::disk('public')->exists($version->file_path)) {
                Storage::disk('public')->delete($version->file_path);
            }
            $version->delete();
        }

        // Delete main physical file
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
            
            // Delete its thumbnail if it was an image
            $thumbPath = 'thumbnails/thumb_' . basename($attachment->file_path);
            if (Storage::disk('public')->exists($thumbPath)) {
                Storage::disk('public')->delete($thumbPath);
            }
        }

        $taskId = $attachment->task_id;
        $attachment->delete();

        // Trigger SSE reload
        Cache::forget("task_detail_{$taskId}");
        Cache::put('tasks_last_updated', microtime(true), 600);

        return response()->json([
            'status' => 'success',
            'message' => 'Attachment berhasil dihapus beserta seluruh versinya'
        ]);
    }
}
