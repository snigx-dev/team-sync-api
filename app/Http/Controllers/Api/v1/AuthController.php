<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\V1BaseController;
use App\Http\Requests\Api\v1\Auth\LoginRequest;
use App\Http\Requests\Api\v1\Auth\RegisterRequest;
use App\Http\Resources\v1\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends V1BaseController
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return $this->apiResponse([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ], 'User registered successfully', 201);
    }

    /**
     * Login user.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        return $this->apiResponse([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ], 'Login successful');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->apiResponse(null, 'Logged out successfully');
    }

    /**
     * Get an authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        return $this->apiResponse(new UserResource($request->user()));
    }
}
