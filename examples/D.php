<?php


namespace PlugRoute\Example;


class D
{
    private $dependecy;

    public function __construct(E $e)
    {
        $this->dependecy = $e;
    }
}