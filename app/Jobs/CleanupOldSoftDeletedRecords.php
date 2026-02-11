<?php

namespace App\Jobs;

use App\Models\Comment;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanupOldSoftDeletedRecords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 300; // 5 minutes

    /**
     * Number of days before permanently deleting soft-deleted records.
     *
     * @var int
     */
    protected int $daysOld;

    /**
     * Create a new job instance.
     */
    public function __construct(int $daysOld = 30)
    {
        $this->daysOld = $daysOld;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $cutoffDate = now()->subDays($this->daysOld);

        // Cleanup tasks
        $deletedTasks = Task::onlyTrashed()
            ->where('deleted_at', '<', $cutoffDate)
            ->forceDelete();

        // Cleanup comments
        $deletedComments = Comment::onlyTrashed()
            ->where('deleted_at', '<', $cutoffDate)
            ->forceDelete();

        // Cleanup teams
        $deletedTeams = Team::onlyTrashed()
            ->where('deleted_at', '<', $cutoffDate)
            ->forceDelete();

        \Log::info('Cleanup of old soft-deleted records completed', [
            'cutoff_date' => $cutoffDate->toDateTimeString(),
            'days_old' => $this->daysOld,
            'deleted_counts' => [
                'tasks' => $deletedTasks,
                'comments' => $deletedComments,
                'teams' => $deletedTeams,
            ],
            'total_deleted' => $deletedTasks + $deletedComments + $deletedTeams,
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        \Log::error('Failed to cleanup old soft-deleted records', [
            'days_old' => $this->daysOld,
            'error' => $exception->getMessage(),
        ]);
    }
}
