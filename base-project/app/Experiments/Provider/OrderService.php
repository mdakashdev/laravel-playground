<?php

namespace App\Experiments\Provider;

class OrderService
{
    public function __construct(
        protected PaymentGateway $paymentGateway
    ) {

    }

    public function payment()
    {
        return $this->paymentGateway->pay();
    }

}
