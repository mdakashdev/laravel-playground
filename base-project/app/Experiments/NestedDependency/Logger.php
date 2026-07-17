<?php
namespace App\Experiments\NestedDependency;

class Logger
{
    public function __construct(
        protected Config $config
    ) {
        dump('Logger created');
    }
}
