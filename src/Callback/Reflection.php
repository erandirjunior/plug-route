<?php

namespace PlugRoute\Callback;

use PlugRoute\Error;
use PlugRoute\Helpers\ValidateHelper;
use PlugRoute\Http\Request;

class Reflection
{
    private Request $request;

    private array $dependencies;

    public function __construct()
    {
        $this->dependencies = [];
    }

    public function setRequest($request): void
    {
        $this->request = $request;
    }

    public function setDependencies($dependencies): void
    {
        $this->dependencies = $dependencies;
    }

    public function callFunction($callback)
    {
        $reflection	= new \ReflectionFunction($callback);
        $args 		= $this->getParameters($reflection);

        return $callback(...$args);
    }

    public function callClass($class)
    {
        if (!ValidateHelper::classExists($class)) {
            return Error::throwException("Error: class {$class} don't exists.");
        }

        $args       = [];
        $construct  = '__construct';

        if (ValidateHelper::methodExists($class, $construct)) {
            $reflection = new \ReflectionMethod($class, $construct);
            $args       = $this->getParameters($reflection);
        }

        return new $class(...$args);
    }

    public function callMethod($object, $method)
    {
        if (!ValidateHelper::methodExists($object, $method)) {
            return Error::throwException("Error: method {$method} don't exists.");
        }

        $reflection = new \ReflectionMethod($object, $method);
        $args 		= $this->getParameters($reflection);

        return $object->$method(...$args);
    }

    private function getParameters($reflection)
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
                $request->setParameter($key, $value);
            }

            $request->setRouteNamed($this->request->getRouteNamed());

            return $request;
        }

        return $this->createObject($namespace);
    }

    private function createObject($namespace)
    {
        if (array_key_exists($namespace, $this->dependencies)) {
            return $this->dependencies[$namespace];
        }

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
}