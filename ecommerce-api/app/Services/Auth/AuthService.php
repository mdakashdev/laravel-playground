<?php

namespace App\Services\Auth;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\Models\User;


class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected RegisterUserAction $registerUserAction,
        protected LoginUserAction $loginUserAction
    ) {
    }

    public function register(array $data): User
    {
        return $this->registerUserAction->execute($data);
    }

    public function login(array $credentials): array
    {

        $user = $this->loginUserAction->execute($credentials);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
