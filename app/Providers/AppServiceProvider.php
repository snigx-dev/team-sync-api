<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\Team;
use App\Policies\CommentPolicy;
use App\Policies\TaskPolicy;
use App\Policies\TeamPolicy;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Morph map
        Relation::enforceMorphMap([
            'task' => \App\Models\Task::class,
            'team' => \App\Models\Team::class,
            'user' => \App\Models\User::class,
        ]);
        // Gates
        Gate::policy(Team::class, TeamPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
    }
}
