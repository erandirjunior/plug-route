<?php

namespace PlugRoute\Callback;

use PlugRoute\Action\ClosureAction;
use PlugRoute\Error;
use PlugRoute\Http\Request;
use ReflectionException;
use ReflectionMethod;

class Reflection
{
    private Request $request;

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * @throws ReflectionException
     */
    public function callFunction(ClosureAction $closureAction)
    {
        $reflection	= new \ReflectionFunction($closureAction->getAction());
        $args 		= $this->getParameters($reflection);

        return $closureAction->getAction()(...$args);
    }

    public function callClass($class)
    {
        if (!class_exists($class)) {
            Error::throwException("Error: class {$class} don't exists.");
        }

        $args = $this->getArgsFromConstructor($class);

        return new $class(...$args);
    }

    public function callMethod($object, $method)
    {
        if (!$this->methodExists($object, $method)) {
            return Error::throwException("Error: method {$method} don't exists.");
        }

        $args = $this->getArgsFromMethod($object, $method);

        return $object->$method(...$args);
    }

    private function getParameters($reflection): array
    {
        $parameters = $reflection->getParameters();
        $args = [];

        $this->getParametersValue($parameters, $args);

        return $args;
    }

    private function getInstance($namespace)
    {
        if ($namespace === 'PlugRoute\Http\Request') {
            return $this->request;
        }

        $request = new $namespace();

        if ($request instanceof Request) {
            foreach ($this->request->parameters() as $key => $value) {
                $request->addParameter($key, $value);
            }

            $request->setRouteNamed($this->request->getAllRouteNamed());

            return $request;
        }

        return $this->createObject($namespace);
    }

    private function createObject($namespace)
    {
        return new $namespace();
    }

    private function getParametersValue($parameters, array &$args): void
    {
        $params = $this->request->parameters();
        $counter = 0;
        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type && !$type->isBuiltin()) {
                $args[] = $this->getInstance($type->getName());
                continue;
            }

            if ($parameter->isArray()) {
                $args[] = $this->request->parameters();
                continue;
            }

            $values = array_values($params);
            $args[] = $values[$counter];
            $counter++;
        }
    }

    private function getArgsFromConstructor($class): array
    {
        $args = [];
        $construct = '__construct';

        if ($this->methodExists($class, $construct)) {
            $args = $this->getArgsFromMethod($class, $construct);
        }

        return $args;
    }

    private function methodExists($class, string $method): bool
    {
        return method_exists($class, $method);
    }

    private function getArgsFromMethod($object, $method): array
    {
        $reflection = new ReflectionMethod($object, $method);
        return $this->getParameters($reflection);
    }
}