<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;

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

       // Already verified হলে কিছুই করবে না
       if ($user->hasVerifiedEmail()) {
           return;
       }

       if ($user->markEmailAsVerified()) {
           event(new Verified($user));
       }
   }
}
