<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ForgotPasswordAction
{
    public function execute(string $email): void
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            throw new NotFoundHttpException('User not found.');
        }

        //using Broker
        $status = Password::sendResetLink([
            'email' => $email,
        ]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new HttpException(400, __($status));
        }
    }

}

