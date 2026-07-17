<?php
namespace App\Experiments\NestedDependency;

class MailService
{
    public function __construct(
       protected Logger $logger
    ) {
        dump('MailService created');
    }
}

