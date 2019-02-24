<?php

namespace PlugRoute\Callback;

use PlugRoute\Error;
use PlugRoute\Helpers\ValidateHelper;
use PlugRoute\Http\Request;
use PlugRoute\Middleware\PlugRouteMiddleware;

class Callback
{
    private $request;

    public function __construct($name)
    {
        $this->request 	= new Request($name);
    }

    public function handleCallback($route, array $urlParameters = [])
    {
		$this->request->setUrlParameter($urlParameters);

		if (!empty($route['middleware'])) {
			$this->callMiddleware($route['middleware']);
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
            	Error::showError('Error: your class should implement PlugRouteMiddleware');
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
        if (ValidateHelper::classExist($class)) {
			$reflection = new \ReflectionMethod($class, "__construct");
			$args 		= $this->getParameters($reflection);
			return new $class(...$args);
		}

		Error::showError("Error: class don't exist.");
    }

    private function callMethod($instance, $method)
    {
        if (ValidateHelper::methodExist($instance, $method)) {
			$reflection = new \ReflectionMethod($instance, $method);
			$args 		= $this->getParameters($reflection);
            return $instance->$method(...$args);
        }

		Error::showError("Error: method don't exist.");
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