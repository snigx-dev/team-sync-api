<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CommentService
{
    /**
     * Get all comments with optional filters.
     */
    public function getAllComments(Model $commentable, array $filters = []): Collection
    {
        return $commentable->comments()
            ->with([
                'user:id,name,email'
            ])
            ->when(isset($filters['search']), function ($query) use ($filters) {
                $query->where('content', 'like', '%' . $filters['search'] . '%');
            })
            ->latest()
            ->get();
    }

    /**
     * Create a new comment.
     */
    public function createComment(Model $commentable, array $data, User $user): Comment
    {
        $comment = $commentable->comments()->create([
            'content' => $data['content'],
            'user_id' => $user->id,
        ]);

        return $comment->load([
            'user:id,name,email'
        ]);
    }

    /**
     * Update comment.
     */
    public function updateComment(Comment $comment, array $data): Comment
    {
        $comment->update($data);

        return $comment->fresh([
            'user:id,name,email'
        ]);
    }

    /**
     * Delete comment.
     */
    public function deleteComment(Comment $comment): bool
    {
        return $comment->delete();
    }
}
