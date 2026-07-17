<?php

namespace App\Experiments\NestedDependency;

class UserController
{
    public function __construct(
        protected MailService $mailService,
        //protected Container $container
    ) {
        dump('UserController created');
    }

    public function register()
    {
       // $this->container->bind('Mail', MailService::class);

        //check object
        //var_dump($this->container->make(MailService::class));

        return 'stored';
    }

}
