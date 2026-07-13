<?php
namespace App\Experiments\DependencyInjection;

class DependencyService
{
    public function __construct(
        protected Logger $logger
    ) {

    }

    public function send()
    {
        return "mail send...";
    }
}
