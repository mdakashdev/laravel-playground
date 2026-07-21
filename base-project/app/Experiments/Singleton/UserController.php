<?php

namespace App\Experiments\Singleton;

class UserController
{
    public function __construct(
        protected Container $container
    ) {
    }

    public function register()
    {
        //here, both (bind and singleton) methods are storage in $bindings[]

        //$this->container->bind('mail', MailService::class);
        $this->container->singleton('mail', MailService::class);

        // get object
        $a = $this->container->make('mail');
        var_dump($a);
        $b = $this->container->make('mail');
        var_dump($b);

        # object(App\Experiments\Singleton\MailService)#320 (0) { }
        # object(App\Experiments\Singleton\MailService)#307 (0) { }
        #
        # bool(false)

        # object(App\Experiments\Singleton\MailService)#320 (0) { }
        # object(App\Experiments\Singleton\MailService)#320 (0) { }
        # bool(true)

        var_dump($a === $b);
        //making object
        //var_dump($this->container->make('mail'));

        return 'done';
    }


}
