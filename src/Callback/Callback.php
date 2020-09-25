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

    private $reflaction;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->reflaction = new Reflection();
    }

    public function handlerCallback(Route $route, array $dependencies = [])
    {
        $this->reflaction->setRequest($this->request);
        $this->reflaction->setDependencies($dependencies);

		if (!empty($route->getMiddlewares())) {
			$this->callMiddleware($route->getMiddlewares());
		}

		if (is_callable($route->getCallback())) {
		    return $this->reflaction->callFunction($route->getCallback());
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
        $object = $this->reflaction->callClass($callback[0]);

        return $this->reflaction->callMethod($object, $callback[1]);
    }
}