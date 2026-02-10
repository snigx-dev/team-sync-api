<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\CommentController;
use App\Http\Controllers\Api\v1\TaskController;
use App\Http\Controllers\Api\V1\TeamController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public routes
    Route::post('/auth/register', [AuthController::class, 'register'])->name('register');
    Route::post('/auth/login', [AuthController::class, 'login'])->name('login');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth routes
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        // Teams routes
        Route::apiResource('teams', TeamController::class);
        Route::post('/teams/{team}/members', [TeamController::class, 'addMember']);
        Route::delete('/teams/{team}/members/{user}', [TeamController::class, 'removeMember']);
        Route::get('teams/{team}/comments', [CommentController::class, 'index']);
        Route::post('teams/{team}/comments', [CommentController::class, 'store']);

        // Tasks routes
        Route::apiResource('tasks', TaskController::class);
        Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
        Route::patch('/tasks/{task}/assign', [TaskController::class, 'assignTask']);
        Route::get('tasks/{task}/comments', [CommentController::class, 'index']);
        Route::post('tasks/{task}/comments', [CommentController::class, 'store']);

        // Comments routes
        Route::apiResource('comments', CommentController::class)->only(['show', 'update', 'destroy']);
    });
});
