<?php

namespace App\Experiments\Provider;

use App\Experiments\Provider\PaymentGateway;

class StripePayment implements PaymentGateway
{

    public function pay()
    {
        return "payment using stripe";
    }
}
