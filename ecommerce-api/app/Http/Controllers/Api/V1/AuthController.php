<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Support\ApiResponse;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        return ApiResponse::success(
            'Validation passed',
            data: $request->validated()
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
