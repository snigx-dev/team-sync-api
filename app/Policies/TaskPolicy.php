<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return $task->creator_id === $user->id ||
               $task->assignee_id === $user->id ||
               ($task->team && $task->team->users()->where('user_id', $user->id)->exists());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return $task->creator_id === $user->id ||
               $task->assignee_id === $user->id ||
               ($task->team && $task->team->owner_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $task->creator_id === $user->id ||
               ($task->team && $task->team->owner_id === $user->id);
    }
}
