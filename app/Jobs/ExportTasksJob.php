<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExportTasksJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $fileName;

    /**
     * Create a new job instance.
     */
    public function __construct($fileName = 'tasks_export.csv')
    {
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("ExportTasksJob: Memulai ekspor data tasks...");

        $tasks = Task::with(['creator', 'assignedUser'])->get();

        // Save path in public storage
        Storage::disk('public')->makeDirectory('exports');
        $relativeFilePath = 'exports/' . $this->fileName;
        $absolutePath = Storage::disk('public')->path($relativeFilePath);

        $file = fopen($absolutePath, 'w');

        // Add UTF-8 BOM for Excel Excel compatibility
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        // CSV headers
        fputcsv($file, [
            'ID Task',
            'Judul',
            'Deskripsi',
            'Status',
            'Prioritas',
            'Ditugaskan Ke',
            'Dibuat Oleh',
            'Tenggat Waktu',
            'Tanggal Dibuat',
        ]);

        foreach ($tasks as $task) {
            fputcsv($file, [
                $task->id,
                $task->title,
                $task->description,
                $task->status,
                $task->priority,
                $task->assignedUser ? $task->assignedUser->name : 'Belum Ditugaskan',
                $task->creator ? $task->creator->name : '-',
                $task->due_date ? $task->due_date->format('Y-m-d') : '-',
                $task->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($file);

        Log::info("ExportTasksJob: Ekspor data berhasil disimpan di {$relativeFilePath}");
    }
}
