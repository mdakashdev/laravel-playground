<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\Auth\AuthService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ){
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register(
            $request->validated()
        );

        return ApiResponse::success(
            message: 'User registered successfully.',
            data: new UserResource($user),
            statusCode: 201
        );
    }

    public function login(LoginRequest $loginRequest)
    {

        $user = $this->authService->login(
            $loginRequest->validated()
        );

        return ApiResponse::success(
            'Login successful.',
            [
                'user' => new UserResource($user['user']),
                'token' => $user['token']
            ]
        );
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return ApiResponse::success(
            'Logged out successfully.'
        );
    }

    public function me(Request $request)
    {
        $user = $this->authService->me($request->user());

        return ApiResponse::success(
            'User profile fetched successfully.',
            new UserResource($user)
        );
    }

    public function refresh()
    {
        return ApiResponse::success('Pending implementation');
    }

    public function forgotPassword()
    {
        return ApiResponse::success('Pending implementation');
    }

    public function resetPassword()
    {
        return ApiResponse::success('Pending implementation');
    }

    public function verifyEmail(Request $request, int $id, string $hash)
    {
        $this->authService->verifyEmail($id, $hash);

        return ApiResponse::success(
            message: 'Email verified successfully.'
        );
    }

    public function resendVerification()
    {
        return ApiResponse::success('Pending implementation');
    }

}
