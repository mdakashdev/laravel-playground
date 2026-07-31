<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register()
    {
        return ApiResponse::success('Pending implementation');
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
