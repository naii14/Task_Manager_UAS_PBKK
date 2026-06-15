<?php

namespace App\Jobs;

use App\Models\TaskAttachment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SimulateVirusScan implements ShouldQueue
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

        Log::info("VirusScan: Memulai pemindaian virus untuk file '{$attachment->file_name}'...");

        // Simulate scanning delay
        sleep(3);

        $fileName = strtolower($attachment->file_name);
        
        // EICAR is standard test string for anti-virus
        if (str_contains($fileName, 'eicar') || str_contains($fileName, 'virus') || str_contains($fileName, 'malware')) {
            Log::warning("VirusScan: BAHAYA! Virus terdeteksi di dalam file '{$attachment->file_name}'! Melakukan karantina/penghapusan.");
            
            // Delete file for security
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
            
            // Delete record or mark as infected
            $attachment->delete();
            Log::info("VirusScan: File terinfeksi berhasil dikarantina dan dihapus.");
        } else {
            Log::info("VirusScan: Pemindaian selesai. File '{$attachment->file_name}' AMAN dan bebas dari malware.");
        }
    }
}
