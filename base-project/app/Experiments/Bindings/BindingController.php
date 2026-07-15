<?php
namespace App\Experiments\Bindings;

use App\Experiments\DependencyInjection\MailService;

class BindingController
{
    public function __construct(
        protected Container $container
    ) {

    }

    public function register()
    {
        $this->container->bind('PaymentGateway', MailService::class);
        //see result
        //dd($this->container);
            var_dump($this->container->make('PaymentGateway'));

        return "successfully registered";
    }

    /**
     * Problem First - amar kache 2 ta payment-gateway ache 1. StripePayment 2. PaypalPayment
     * ami orderService a StripePayment use korlam, but after few days client want PaypalPayment
     * then amader all implemention a change korte hobe.
     *
     *
     * Today - Register kora.
     *  aar seta bind diye kora jai, and binding array te store kore; as like phone contact (name:contact-number)
     *  amra contact-number mone rakhi na, amra name ta mone rakhi etc.
     *
     * bind er 2 ta responsiblity - register and resolve kora, but today register korbo.
     *
     *
     * Flow: container -> bind -> bindings
     *
     * abstract = name, concrete = class, shared - singleton !!
     */
}
