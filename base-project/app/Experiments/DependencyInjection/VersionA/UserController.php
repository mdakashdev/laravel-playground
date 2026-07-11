<?php
namespace App\Experiments\DependencyInjection\VersionA;

use App\Experiments\DependencyInjection\MailService;

class UserController
{
    public function register()
    {
        $mailService = new MailService();
        return $mailService->send();
    }
}
