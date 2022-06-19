<?php

namespace PlugRoute\Test\Mock;

class Session extends \PlugRoute\Http\Globals\Session
{
    public function __construct()
    {
        $_SESSION = [
            'name' => 'Erandir Junior',
            'age' => 23,
            'email' => 'aefs12junior@gmail.com'
        ];
        parent::__construct();
    }
}