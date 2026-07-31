<?php

namespace App\Services\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\Models\User;


class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected RegisterUserAction $registerUserAction
    ) {
    }

    public function register(array $data): User
    {
        return $this->registerUserAction->execute($data);
    }
}
