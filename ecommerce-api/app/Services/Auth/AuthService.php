<?php

namespace App\Services\Auth;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\Models\User;
use Illuminate\Auth\Events\Verified;

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
        $user = User::findOrFail($id);

        // Verify the email hash
        if (! hash_equals(
            (string) $hash,
            sha1($user->getEmailForVerification())
        )) {
            abort(403, 'Invalid verification link.');
        }

        // Already verified হলে কিছুই করবে না
        if ($user->hasVerifiedEmail()) {
            return;
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
    }

}
