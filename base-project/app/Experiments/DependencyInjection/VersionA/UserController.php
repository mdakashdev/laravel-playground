<?php
namespace App\Experiments\DependencyInjection\VersionA;

class UserController
{
    public function register()
    {
        $mailService = new MailService();
        return $mailService->send();
    }
}
