<?php

namespace App\Experiments\NestedDependency;

class AppController
{
    public function __construct(
        protected MailService $mailService
    ) {
        dump('UserController created');
    }
}
