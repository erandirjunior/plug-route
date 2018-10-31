<?php

namespace PlugRoute\Services;

use PlugRoute\Exceptions\ClassException;
use PlugRoute\Exceptions\MethodException;
use PlugRoute\Helpers\ValidateHelper;
use PlugRoute\Http\HttpRequest;
use PlugRoute\Http\HttpResponse;

class CallbackService
{
    private $request;

    private $response;

    public function __construct()
    {
        $this->request = new HttpRequest();
        $this->response = new HttpResponse();
    }

    public function handleCallback($route, $parameters = null)
    {
        $this->request->setBody($parameters);

        if (is_callable($route['callback'])) {
            return $this->callFunction($route['callback']);
        }

        return $this->handleObject($route);
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