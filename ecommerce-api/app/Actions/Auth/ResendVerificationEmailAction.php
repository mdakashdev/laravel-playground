<?php

namespace App\Actions\Auth;

use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResendVerificationEmailAction
{
    public function execute(string $email): void
    {
        //1. Email দিয়ে user খুঁজবে।
        $user = User::where('email', $email)->first();

        //2. User না থাকলে `404` exception।
        if (! $user) {
            throw new NotFoundHttpException('User not found.');
        }
        //3. যদি already verified হয় → `400` exception।
        if ($user->hasVerifiedEmail()) {
            throw new HttpException(400, 'Email is already verified.');
        }
        /// 4. `sendEmailVerificationNotification()` call করবে।
        $user->sendEmailVerificationNotification();
    }
}
