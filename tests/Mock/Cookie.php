<?php

namespace PlugRoute\Test\Mock;

class Cookie extends \PlugRoute\Http\Globals\Cookie
{
    public function __construct()
    {
        $_COOKIE = [
            'test' => [
                'name' => 'Erandir Junior',
                'age' => 23,
                'email' => 'aefs12junior@gmail.com'
            ]
        ];
        parent::__construct();
    }
}