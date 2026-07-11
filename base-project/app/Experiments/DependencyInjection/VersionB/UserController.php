<?php
namespace App\Experiments\DependencyInjection\VersionB;

use App\Experiments\DependencyInjection\MailService;

class UserController
{
    public function __construct(
        protected MailService $mail
    ) {
    }

    public function register()
    {
        return $this->mail->send();
    }
}
