<?php

namespace App\Services\Auth;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\Actions\Auth\ResendVerificationEmailAction;
use App\Actions\Auth\VerifyEmailAction;
use App\Models\User;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected RegisterUserAction $registerUserAction,
        protected LoginUserAction $loginUserAction,
        protected VerifyEmailAction $verifyEmailAction,
        protected ResendVerificationEmailAction $resendVerificationEmailAction
    ) {
    }

    public function register(array $data): User
    {
        $user = $this->registerUserAction->execute($data);
        $user->sendEmailVerificationNotification();

        return $user;
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

    public function me(User $user): User
    {
        return $user;
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }

    public function verifyEmail(int $id, string $hash): void
    {
        $this->verifyEmailAction->execute($id, $hash);
    }

    public function resendVerificationEmail(string $email): void
    {
        $this->resendVerificationEmailAction->execute($email);
    }

}
