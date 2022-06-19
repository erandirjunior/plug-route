<?php

namespace PlugRoute\Test\Mock;

class Get extends \PlugRoute\Http\Globals\Get
{
    public function __construct()
    {
        $_GET = [
            'test' => [
                'name' => 'Erandir Junior',
                'age' => 23,
                'email' => 'aefs12junior@gmail.com'
            ]
        ];
        parent::__construct();
    }
}