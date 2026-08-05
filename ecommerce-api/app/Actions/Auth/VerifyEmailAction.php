<?php

namespace App\Actions\Auth;

use App\Models\User;

class VerifyEmailAction
{
   public function execute(int $id, string $hash): void
   {
       $user = User::findOrFail($id);

       // Verify the email hash
       if (! hash_equals(
           (string) $hash,
           sha1($user->getEmailForVerification())
       )) {
           abort(403, 'Invalid verification link.');
       }

       if (! $user->hasVerifiedEmail()) {
           $user->markEmailAsVerified();
       }

   }
}
