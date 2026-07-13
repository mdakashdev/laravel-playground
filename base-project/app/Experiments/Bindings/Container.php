<?php
namespace App\Experiments\Bindings;

class Container
{
    private array $bindings = [];

    public function bind($name, $class)
    {
        $this->bindings[$name] = $class;
    }
}

