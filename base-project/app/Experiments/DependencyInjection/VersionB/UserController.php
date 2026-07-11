<?php
namespace App\Experiments\DependencyInjection\VersionB;

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
