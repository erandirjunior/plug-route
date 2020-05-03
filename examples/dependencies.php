<?php

return [
    'PlugRoute\Example\Dependency\ConstructDependency' => new \PlugRoute\Example\Dependency\ConstructDependency(
        new \PlugRoute\Example\Dependency\FirstDependency()
    )
];