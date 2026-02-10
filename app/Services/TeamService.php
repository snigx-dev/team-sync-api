<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TeamService
{
    /**
     * Get all teams for a user.
     */
    public function getAllTeams(User $user): Collection
    {
        return Team::with(['owner:id,name,email', 'users:id,name,email'])
            ->where('owner_id', $user->id)
            ->orWhereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();
    }

    /**
     * Create a new team.
     */
    public function createTeam(array $data, User $user): Team
    {
        $team = Team::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'owner_id' => $user->id,
        ]);

        // Add owner as a member with owner role
        $team->users()->attach($user->id, ['role' => 'owner']);

        return $team->load(['owner:id,name,email', 'users:id,name,email']);
    }

    /**
     * Update team.
     */
    public function updateTeam(Team $team, array $data): Team
    {
        $team->update($data);

        return $team->fresh(['owner:id,name,email', 'users:id,name,email']);
    }

    /**
     * Delete team.
     */
    public function deleteTeam(Team $team): bool
    {
        return $team->delete();
    }

    /**
     * Add a member to the team.
     */
    public function addMember(Team $team, int $userId, string $role): void
    {
        $team->users()->syncWithoutDetaching([
            $userId => ['role' => $role],
        ]);
    }

    /**
     * Remove a member from the team.
     */
    public function removeMember(Team $team, int $userId): void
    {
        $team->users()->detach($userId);
    }
}
