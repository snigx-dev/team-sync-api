<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\V1BaseController;
use App\Http\Requests\Api\v1\Comment\GetCommentsRequest;
use App\Http\Requests\Api\v1\Comment\StoreCommentRequest;
use App\Http\Requests\Api\v1\Comment\UpdateCommentRequest;
use App\Http\Resources\v1\CommentResource;
use App\Models\Comment;
use App\Models\Task;
use App\Models\Team;
use App\Services\CommentService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CommentController extends V1BaseController
{
    public function __construct(
        protected CommentService $commentService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(GetCommentsRequest $request): JsonResponse
    {
        $parent = $this->getCommentableModel();
        $comments = $this->commentService->getAllComments($parent, $request->validated());

        return $this->apiResponse(CommentResource::collection($comments));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $parent = $this->getCommentableModel();

        $comment = $this->commentService->createComment(
            $parent,
            $request->validated(),
            $request->user()
        );

        return $this->apiResponse(new CommentResource($comment), 'Comment created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment): JsonResponse
    {
        $comment->load([
            'user:id,name,email',
            'commentable',
        ]);

        return $this->apiResponse(new CommentResource($comment));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $comment = $this->commentService->updateComment($comment, $request->validated());

        return $this->apiResponse(new CommentResource($comment), 'Comment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        Gate::authorize('delete', $comment);

        $this->commentService->deleteComment($comment);

        return $this->apiResponse(null, 'Comment deleted successfully', 204);
    }

    /**
     * Helper to resolve the polymorphic parent from the route
     */
    protected function getCommentableModel(): Model
    {
        $task = request()->route('task');
        $team = request()->route('team');

        return match (true) {
            $task instanceof Task => $task,
            $team instanceof Team => $team,

            is_numeric($task) => Task::findOrFail($task),
            is_numeric($team) => Team::findOrFail($team),

            default => abort(404, 'Commentable parent not found'),
        };
    }
}
