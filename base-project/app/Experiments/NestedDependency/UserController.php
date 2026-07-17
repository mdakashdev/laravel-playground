<?php

namespace App\Experiments\NestedDependency;

class UserController
{
    public function __construct(
        protected MailService $mailService
    ) {
        dump('UserController created');
    }

}
