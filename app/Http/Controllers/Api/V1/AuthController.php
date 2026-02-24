<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function register(RegisterRequest $request)
    {
        $data = $this->authService->register($request->validated());

        return (new AuthResource($data))
            ->response()
            ->setStatusCode(201);
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());

        return new AuthResource($data);
    }

    public function logout()
    {
        $this->authService->logout(auth()->user());

        return response()->json([
            'message' => 'Déconnexion réussie.'
        ]);
    }
}
