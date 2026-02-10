<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\V1BaseController;
use App\Http\Requests\Api\v1\Team\StoreTeamRequest;
use App\Http\Requests\Api\v1\Team\UpdateTeamRequest;
use App\Http\Resources\v1\TeamResource;
use App\Models\Team;
use App\Services\TeamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TeamController extends V1BaseController
{
    public function __construct(
        protected TeamService $teamService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $teams = $this->teamService->getAllTeams($request->user());

        return $this->apiResponse(TeamResource::collection($teams));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeamRequest $request): JsonResponse
    {
        $team = $this->teamService->createTeam(
            $request->validated(),
            $request->user()
        );

        return $this->apiResponse(new TeamResource($team), 'Team created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team): JsonResponse
    {
        Gate::authorize('view', $team);

        $team->load([
            'owner:id,name,email',
            'users:id,name,email',
            'tasks' => function ($query) {
                $query->select('id', 'title', 'status', 'priority', 'team_id')
                      ->with('assignee:id,name,email');
            }
        ]);

        return $this->apiResponse(new TeamResource($team));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamRequest $request, Team $team): JsonResponse
    {
        $this->authorize('update', $team);

        $team = $this->teamService->updateTeam($team, $request->validated());

        return $this->apiResponse(new TeamResource($team), 'Team updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team): JsonResponse
    {
        Gate::authorize('delete', $team);

        $this->teamService->deleteTeam($team);

        return $this->apiResponse(null, 'Team deleted successfully');
    }

    /**
     * Add a member to the team.
     */
    public function addMember(Request $request, Team $team): JsonResponse
    {
        Gate::authorize('update', $team);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:member,admin',
        ]);

        $this->teamService->addMember($team, $request->user_id, $request->role);

        return $this->apiResponse(null, 'Member added successfully');
    }

    /**
     * Remove a member from the team.
     */
    public function removeMember(Team $team, int $userId): JsonResponse
    {
        Gate::authorize('update', $team);

        $this->teamService->removeMember($team, $userId);

        return $this->apiResponse(null, 'Member removed successfully');
    }
}
