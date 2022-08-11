<?php

namespace PlugRoute\Test\Mock;

class Server extends \PlugRoute\Http\Globals\Server
{
    public function __construct()
    {
        $_FILES = [
            'test' => [
                'name' => 'Erandir Junior',
                'age' => 23,
                'email' => 'aefs12junior@gmail.com'
            ]
        ];
        parent::__construct();
    }
}