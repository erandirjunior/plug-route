<?php

namespace PlugRoute\Test\Mock;

class File extends \PlugRoute\Http\Globals\File
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