<?php

namespace App\Experiments\NestedDependency;

class Container
{
    public function make11($name)
    {
        $reflection = new \ReflectionClass($name);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return;
        }

        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();
            var_dump($type);
            $class = $type->getName();
            dump(class_basename($class));
            $this->make($class);
        }
    }

    public function make($name)
    {

        $reflection = new \ReflectionClass($name);
        var_dump($reflection);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $name;
        }

        $dependencies = [];
        foreach ($constructor->getParameters() as $parameter) {

            $type = $parameter->getType()->getName();

            $dependencies[] = $this->make($type); // recursion

            //new MailService(new Logger(new Config));
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}
