<?php

namespace PlugRoute\Example\Dependency;

use PlugRoute\Http\Request;

class MyService
{
    public function __construct(ConstructDependency $dependencyConstruct)
    {
        $dependencyConstruct->apresentation();
    }

    public function apresentation(Request $request)
    {
        echo "MyService class, parameters:";

        echo "<pre>";
        var_dump($request->parameters());
    }
}