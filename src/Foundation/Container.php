<?php

namespace AcmeWidgetCo\Foundation;

use Exception;
use ReflectionClass;
use ReflectionNamedType;

class Container
{
    /**
     * Resolve a class and its dependencies.
     *
     * @template T of object
     * @param class-string<T> $class 
     * @return T 
     * @throws Exception 
     */
    public function resolve(string $class)
    {
        $reflector = new ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$class} is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $class;
        }

        $parameters   = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if (!$type instanceof ReflectionNamedType) {
                throw new Exception("Cannot resolve untyped dependency \${$parameter->getName()} for class {$class}.");
            }

            if ($type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Cannot resolve primitive dependency \${$parameter->getName()} for class {$class}.");
                }
            } else {
                /** @var class-string<object> $dependencyClass */
                $dependencyClass = $type->getName();
                $dependencies[] = $this->resolve($dependencyClass);
            }
        }

        return $reflector->newInstanceArgs($dependencies);
    }
}
