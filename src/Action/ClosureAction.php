<?php

namespace PlugRoute\Action;

use Closure;

class ClosureAction
{
    private Closure $action;

    public function __construct(Closure $closure)
    {
        $this->action = $closure;
    }

    public function getAction(): Closure
    {
        return $this->action;
    }
}