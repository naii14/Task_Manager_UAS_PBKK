<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class BulkUpdateTasksJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $taskIds;
    protected $status;

    /**
     * Create a new job instance.
     */
    public function __construct(array $taskIds, string $status)
    {
        $this->taskIds = $taskIds;
        $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("BulkUpdateTasksJob: Mulai memperbarui status " . count($this->taskIds) . " tasks menjadi {$this->status}");

        Task::whereIn('id', $this->taskIds)->update([
            'status' => $this->status
        ]);

        // Trigger SSE reload
        Cache::put('tasks_last_updated', microtime(true), 600);

        Log::info("BulkUpdateTasksJob: Status tasks berhasil diperbarui.");
    }
}
