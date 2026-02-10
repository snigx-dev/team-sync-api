<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use App\Models\Task;
use App\Models\Team;

class CommentPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // User can delete their own comment
        if ($comment->user_id === $user->id) {
            return true;
        }

        // Owner of commentable can delete comment
        $commentable = $comment->commentable;
        
        if ($commentable instanceof Task) {
            return $commentable->creator_id === $user->id;
        }
        
        if ($commentable instanceof Team) {
            return $commentable->owner_id === $user->id;
        }

        return false;
    }
}
