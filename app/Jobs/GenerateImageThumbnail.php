<?php

namespace App\Jobs;

use App\Models\TaskAttachment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateImageThumbnail implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $attachmentId;

    /**
     * Create a new job instance.
     */
    public function __construct($attachmentId)
    {
        $this->attachmentId = $attachmentId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $attachment = TaskAttachment::find($this->attachmentId);

        if (!$attachment) {
            return;
        }

        $filePath = $attachment->file_path; // relative to 'local' or 'public' storage disk
        
        // Ensure the file exists
        if (!Storage::disk('public')->exists($filePath)) {
            Log::warning("GenerateImageThumbnail: File {$filePath} tidak ditemukan di disk public.");
            return;
        }

        $mimeType = $attachment->mime_type;
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($mimeType, $allowedMimes)) {
            Log::info("GenerateImageThumbnail: Skip non-image file {$attachment->file_name} ({$mimeType})");
            return;
        }

        Log::info("GenerateImageThumbnail: Mulai memproses thumbnail untuk {$attachment->file_name}");

        $absolutePath = Storage::disk('public')->path($filePath);
        
        // Load image based on mime type
        $srcImg = null;
        switch ($mimeType) {
            case 'image/jpeg':
                $srcImg = @imagecreatefromjpeg($absolutePath);
                break;
            case 'image/png':
                $srcImg = @imagecreatefrompng($absolutePath);
                break;
            case 'image/gif':
                $srcImg = @imagecreatefromgif($absolutePath);
                break;
            case 'image/webp':
                $srcImg = @imagecreatefromwebp($absolutePath);
                break;
        }

        if (!$srcImg) {
            Log::error("GenerateImageThumbnail: Gagal meload gambar {$attachment->file_name}");
            return;
        }

        $width = imagesx($srcImg);
        $height = imagesy($srcImg);

        // Desired size: Max 150x150
        $thumbWidth = 150;
        $thumbHeight = 150;

        $ratio = min($thumbWidth / $width, $thumbHeight / $height);
        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);

        $thumbImg = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG and GIF
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($thumbImg, false);
            imagesavealpha($thumbImg, true);
            $transparent = imagecolorallocatealpha($thumbImg, 255, 255, 255, 127);
            imagefilledrectangle($thumbImg, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Save thumbnail in storage/app/public/thumbnails
        Storage::disk('public')->makeDirectory('thumbnails');
        $thumbName = 'thumb_' . basename($filePath);
        $thumbRelativePath = 'thumbnails/' . $thumbName;
        $thumbAbsolutePath = Storage::disk('public')->path($thumbRelativePath);

        $saved = false;
        switch ($mimeType) {
            case 'image/jpeg':
                $saved = imagejpeg($thumbImg, $thumbAbsolutePath, 80);
                break;
            case 'image/png':
                $saved = imagepng($thumbImg, $thumbAbsolutePath, 6);
                break;
            case 'image/gif':
                $saved = imagegif($thumbImg, $thumbAbsolutePath);
                break;
            case 'image/webp':
                $saved = imagewebp($thumbImg, $thumbAbsolutePath, 80);
                break;
        }

        // Free up memory
        imagedestroy($srcImg);
        imagedestroy($thumbImg);

        if ($saved) {
            Log::info("GenerateImageThumbnail: Thumbnail berhasil dibuat di {$thumbRelativePath}");
            
            // We can optionally store it in cache or database, but simply saving the file 'thumb_filename.png' in the same folder or in subfolder is sufficient. 
            // In the frontend, if the file is an image, we can fetch `/storage/thumbnails/thumb_filename` directly!
        } else {
            Log::error("GenerateImageThumbnail: Gagal menyimpan file thumbnail.");
        }
    }
}
