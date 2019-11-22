<?php

namespace PlugRoute\Callback;

use PlugRoute\Error;
use PlugRoute\Helpers\ValidateHelper;
use PlugRoute\Http\Request;
use PlugRoute\Middleware\PlugRouteMiddleware;

class Callback
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request 	= $request;
    }

    public function handleCallback($route)
    {
		if (!empty($route['middlewares'])) {
			$this->callMiddleware($route['middlewares']);
		}

		if (is_callable($route['callback'])) {
            return $this->callFunction($route['callback']);
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
        $callback = explode("@", $route['callback']);
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
				$args[] 		= $object === 'PlugRoute\Http\Request' ? $this->request : new $object();
			}
		}

		return $args;
    }
}