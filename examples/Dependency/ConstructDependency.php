<?php

namespace PlugRoute\Example\Dependency;

class ConstructDependency
{
    private $firstDependency;

    public function __construct(FirstDependency $firstDependency)
    {
        $this->firstDependency = $firstDependency;
    }

    public function apresentation()
    {
        echo $this->firstDependency->apresentation();

        echo "Construct dependency<br>";
    }
}