<?php
namespace App\Experiments\PhpReflection;

class MailService
{
    public function __construct(
        protected Logger $logger,
        protected Cache $cache
    ) {

    }

}
