<?php

namespace PlugRoute\Callback;

use PlugRoute\Error;
use PlugRoute\Helpers\ValidateHelper;
use PlugRoute\Http\Request;
use PlugRoute\Middleware\PlugRouteMiddleware;
use PlugRoute\Route;

class Callback
{
    private $request;

    private $dependencies;

    public function __construct(Request $request)
    {
        $this->request 	= $request;
    }

    public function handleCallback(Route $route, array $dependencies = [])
    {
        $this->dependencies = $dependencies;

		if (!empty($route->getMiddlewares())) {
			$this->callMiddleware($route->getMiddlewares());
		}

		if (is_callable($route->getCallback())) {
            return $this->callFunction($route->getCallback());
        }

        return $this->handleObject($route);
    }

    private function callMiddleware($middlewares)
    {
        foreach ($middlewares as $middleware) {
            $obj = new $middleware();

            if (!($obj instanceof PlugRouteMiddleware)) {
                $message = "Error: the class {$middleware} should implement PlugRouteMiddleware.";

                return Error::throwException($message);
            }

            $this->request = $obj->handle($this->request);
        }
    }

    private function handleObject($route)
    {
        $callback = explode("@", $route->getCallback());
        $instance = $this->createObject($callback[0]);
        return $this->callMethod($instance, $callback[1]);
    }

    private function createObject($class)
    {
        if (!ValidateHelper::classExists($class)) {
			return Error::throwException("Error: class {$class} don't exists.");
		}

		$args = [];
		$construct = '__construct';

		if (ValidateHelper::methodExists($class, $construct)) {
			$reflection = new \ReflectionMethod($class, $construct);
			$args       = $this->getParameters($reflection);
		}

		return new $class(...$args);
	}

    private function callMethod($instance, $method)
    {
        if (!ValidateHelper::methodExists($instance, $method)) {
			return Error::throwException("Error: method {$method} don't exists.");
		}

		$reflection = new \ReflectionMethod($instance, $method);
		$args 		= $this->getParameters($reflection);

		return $instance->$method(...$args);
	}

    private function callFunction($function)
    {
		$reflection	= new \ReflectionFunction($function);
		$args 		= $this->getParameters($reflection);
        return $function(...$args);
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