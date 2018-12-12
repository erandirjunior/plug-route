<?php

namespace PlugRoute\Callback;

use PlugRoute\Exceptions\ClassException;
use PlugRoute\Exceptions\MethodException;
use PlugRoute\Helpers\ValidateHelper;
use PlugRoute\Http\Request;
use PlugRoute\Http\Response;
use PlugRoute\Middleware\PlugRouteMiddleware;

class Callback
{
    private $request;

    private $response;

    public function __construct($name)
    {
        $this->request = new Request($name);
        $this->response = new Response();
    }

    public function handleCallback($route, $parameters = null)
    {
        $this->request->setUrlParameter($parameters);

		$this->callMiddleware($route['middleware']);

        if (is_callable($route['callback'])) {
            return $this->callFunction($route['callback']);
        }

        return $this->handleObject($route);
    }

    private function callMiddleware($middlewares)
    {
    	$func = $this->getFunc();

        foreach ($middlewares as $middleware) {
            $obj = new $middleware();

            if (!($obj instanceof PlugRouteMiddleware)) {
                throw new \Exception('Error: your class should implement PlugRouteMiddleware');
            }

            $obj->handle($this->request, $func);
        }
    }

    public function getFunc()
    {
        return function($request) {
            $this->request = $request;
            return function(){};
        };
    }

    private function handleObject($route)
    {
        $callback = explode("@", $route['callback']);
        $instance = $this->createObject($callback[0]);
        return $this->callMethod($instance, $callback[1]);
    }

    private function createObject($class)
    {
        if (!ValidateHelper::classExist($class)) {
            throw new ClassException("Error: class don't exist.");
        }
        return new $class;
    }

    private function callMethod($instance, $method)
    {
        if (ValidateHelper::methodExist($instance, $method)) {
            return $instance->$method($this->request, $this->response);
        }
        throw new MethodException("Error: method don't exist.");
    }

    private function callFunction($function)
    {
        return $function($this->request, $this->response);
    }
}