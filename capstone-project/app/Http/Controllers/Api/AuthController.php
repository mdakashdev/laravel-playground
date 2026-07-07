<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * @param  AuthService  $authService
     */
    public function __construct(
        protected AuthService $authService
    ) {}

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register(
            $request->validated()
        );

        return new UserResource($user);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login(
            $request->validated()
        );

        return response()->json($result, 200);
    }

    public function update(UpdateUserRequest $request)
    {
        $result = $this->authService->updateProfile(
            $request->user(),
            $request->validated()
        );

        return new UserResource($result);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        //$data = $request->validated();
        //dd($data);
        //dd($request->user());
        return $this->authService->changePassword(
            $request->user(),
            $request->validated()
        );

    }
}
