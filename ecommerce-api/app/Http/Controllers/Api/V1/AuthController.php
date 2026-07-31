<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\Auth\AuthService;
use App\Support\ApiResponse;

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

    public function login()
    {
        return ApiResponse::success('Pending implementation');
    }

    public function logout()
    {
        return ApiResponse::success('Pending implementation');
    }

    public function me()
    {
        return ApiResponse::success('Pending implementation');
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

    public function verifyEmail()
    {
        return ApiResponse::success('Pending implementation');
    }

    public function resendVerification()
    {
        return ApiResponse::success('Pending implementation');
    }

}
