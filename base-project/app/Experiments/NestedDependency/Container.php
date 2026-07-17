<?php

namespace App\Experiments\NestedDependency;

class Container
{
    private array $bindings = [];

    public function bind($name, $class)
    {
        $this->bindings[$name] = $class;
    }

    /**
     * @throws \ReflectionException
     */
    public function make($name)
    {
        $reflection = new \ReflectionClass($name);

        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $name;
        }

        $dependencies = [];
        //var_dump($constructor->getParameters());
        foreach ($constructor->getParameters() as $parameter) {

            $type = $parameter->getType()->getName();

            $dependencies[] = $this->make($type); // recursion
        }
        return $reflection->newInstanceArgs($dependencies);
    }
}
