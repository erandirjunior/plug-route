<?php

namespace PlugRoute\Callback;

use PlugRoute\Error;
use PlugRoute\Http\Request;
use PlugRoute\Middleware\PlugRouteMiddleware;
use PlugRoute\Route;

class Callback
{
    private Request $request;

    private Reflection $reflection;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->reflection = new Reflection();
    }

    public function handlerCallback(Route $route, array $dependencies = [])
    {
        $this->reflection->setRequest($this->request);
        $this->reflection->setDependencies($dependencies);

		if (!empty($route->getMiddlewares())) {
			$this->callMiddleware($route->getMiddlewares());
		}

		if (is_callable($route->getCallback())) {
		    return $this->reflection->callFunction($route->getCallback());
        }

        return $this->handlerObject($route);
    }

    private function callMiddleware($middlewares)
    {
        foreach ($middlewares as $middleware) {
            $obj = new $middleware();

            if (!($obj instanceof PlugRouteMiddleware)) {
                $message = "Error: the class {$middleware} must implement PlugRouteMiddleware.";

                return Error::throwException($message);
            }

            $obj->handler($this->request);
        }
    }

    private function handlerObject($route)
    {
        $callback = explode("@", $route->getCallback());
        $object = $this->reflection->callClass($callback[0]);

        return $this->reflection->callMethod($object, $callback[1]);
    }
}