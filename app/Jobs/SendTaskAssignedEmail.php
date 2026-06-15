<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTaskAssignedEmail implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $task;

    /**
     * Create a new job instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $task = $this->task;
        $assignedUser = $task->assignedUser;

        if ($assignedUser) {
            Log::info("EMAIL NOTIFICATION: Mengirim email penugasan ke {$assignedUser->email} untuk task ID: {$task->id} ('{$task->title}')");
            
            // In a real application, you'd call Mail::to($assignedUser)->send(new TaskAssignedMail($task));
        }
    }
}
