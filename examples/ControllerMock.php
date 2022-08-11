<?php

namespace PlugRoute\Example;

use PlugRoute\Http\Request;
use PlugRoute\Http\Response;

class ControllerMock
{
    public function myMethod(Request $request, Response $response)
    {
        return $response->setStatusCode(201)->json([
            'Product sent' => $request->parameter('productId')
        ]);
    }
    public function test(Request $request, Response $response)
    {
        return 'Route defined in json file.';
    }
}