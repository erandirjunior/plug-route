<?php

namespace PlugRoute\Services;

use PlugRoute\Exceptions\ClassException;
use PlugRoute\Exceptions\MethodException;
use PlugRoute\Helpers\ValidateHelper;

class CallbackService
{
    private $params;

    public function __construct($params = null)
    {
        $this->params = [];

        if (!is_null($params) && is_array($params)) {
            foreach ($params as $key => $value) {
                $this->params[$key] = $value;
            }
        }

        $this->handleCallback();
    }

    public function handleCallback($route)
    {
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
            return $instance->$method();
        }
        throw new MethodException("Error: method don't exist.");
    }

    private function callFunction($function)
    {
        return $function($this->data);
    }
}