<?php

namespace PlugRoute\Callback;

use PlugRoute\Error;
use PlugRoute\Helpers\ValidateHelper;

class Reflection
{
    private $request;

    private $dependencies;

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
        $params = $reflection->getParameters();
        $args 	= [];

        foreach ($params as $param) {
            $type = $param->getType();

            if (!$type->isBuiltin()) {
                $class 			= new \ReflectionClass((string) $type);
                $namespace[] 	= $class->getNamespaceName();
                $namespace[] 	= $class->getShortName();
                $object 		= implode('\\', $namespace);
                $namespace 		= [];
                $args[] 		= $this->getInstanceIfNamespaceIsRequest($object);
            }
        }

        return $args;
    }

    private function getInstanceIfNamespaceIsRequest($namespace)
    {
        if ($namespace === 'PlugRoute\Http\Request') {
            return $this->request;
        }

        return $this->getInstance($namespace);
    }

    private function getInstance($namespace)
    {
        if (array_key_exists($namespace, $this->dependencies)) {
            return $this->dependencies[$namespace];
        }

        return new $namespace();
    }
}