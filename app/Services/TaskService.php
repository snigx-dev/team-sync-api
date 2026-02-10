<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    /**
     * Get all tasks for a user with filters.
     */
    public function getAllTasks(User $user, array $filters = []): Collection
    {
        $query = Task::with([
            'creator:id,name,email',
            'assignee:id,name,email',
            'team:id,name',
        ])
            ->where(function ($q) use ($user) {
                $q->where('creator_id', $user->id)
                    ->orWhere('assignee_id', $user->id)
                    ->orWhereHas('team.users', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            });

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['team_id'])) {
            $query->where('team_id', $filters['team_id']);
        }

        if (isset($filters['assignee_id'])) {
            $query->where('assignee_id', $filters['assignee_id']);
        }

        return $query->latest('created_at')->get();
    }

    /**
     * Get paginated tasks for a user with filters.
     */
    public function getPaginatedTasks(User $user, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Task::with([
            'creator:id,name,email',
            'assignee:id,name,email',
            'team:id,name',
        ])
            ->where(function ($q) use ($user) {
                $q->where('creator_id', $user->id)
                    ->orWhere('assignee_id', $user->id)
                    ->orWhereHas('team.users', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            });

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['team_id'])) {
            $query->where('team_id', $filters['team_id']);
        }

        if (isset($filters['assignee_id'])) {
            $query->where('assignee_id', $filters['assignee_id']);
        }

        return $query->latest('created_at')->paginate($perPage);
    }

    /**
     * Create a new task.
     */
    public function createTask(array $data, User $user): Task
    {
        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? Task::STATUS_TODO,
            'priority' => $data['priority'] ?? Task::PRIORITY_MEDIUM,
            'due_date' => $data['due_date'] ?? null,
            'team_id' => $data['team_id'] ?? null,
            'creator_id' => $user->id,
            'assignee_id' => $data['assignee_id'] ?? null,
        ]);

        return $task->load([
            'creator:id,name,email',
            'assignee:id,name,email',
            'team:id,name',
        ]);
    }

    /**
     * Update task.
     */
    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);

        return $task->fresh([
            'creator:id,name,email',
            'assignee:id,name,email',
            'team:id,name',
        ]);
    }

    /**
     * Delete task.
     */
    public function deleteTask(Task $task): bool
    {
        return $task->delete();
    }

    /**
     * Update task status.
     */
    public function updateStatus(Task $task, string $status): Task
    {
        $task->update(['status' => $status]);

        return $task->fresh([
            'creator:id,name,email',
            'assignee:id,name,email',
            'team:id,name',
        ]);
    }

    /**
     * Assign task to a user.
     */
    public function assignTask(Task $task, int $assigneeId): Task
    {
        $task->update(['assignee_id' => $assigneeId]);

        return $task->fresh([
            'creator:id,name,email',
            'assignee:id,name,email',
            'team:id,name',
        ]);
    }
}
