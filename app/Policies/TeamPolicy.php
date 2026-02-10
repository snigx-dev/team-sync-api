<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Team $team): bool
    {
        return $team->owner_id === $user->id ||
               $team->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Team $team): bool
    {
        return $team->owner_id === $user->id ||
               $team->users()
                   ->wherePivot('user_id', $user->id)
                   ->wherePivot('role', 'admin')
                   ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Team $team): bool
    {
        return $team->owner_id === $user->id;
    }
}
