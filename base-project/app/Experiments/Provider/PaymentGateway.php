<?php

namespace App\Experiments\Provider;

interface PaymentGateway
{
    public function pay();
}
