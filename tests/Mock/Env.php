<?php

namespace PlugRoute\Test\Mock;

class Env extends \PlugRoute\Http\Globals\Env
{
    public function __construct()
    {
        $_ENV = [
            'test' => [
                'name' => 'Erandir Junior',
                'age' => 23,
                'email' => 'aefs12junior@gmail.com'
            ]
        ];
        parent::__construct();
    }
}