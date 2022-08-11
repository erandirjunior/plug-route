<?php

namespace PlugRoute\Test\Mock;

class ObjectMock
{
    public InjectClass $injectClass;

    public function __construct(InjectClass $injectClass)
    {
        $this->injectClass = $injectClass;
    }

    public function run()
    {
        return 'working method';
    }

    public function injetMethod(int $id, array $params): string
    {
        return 'user id: '.$id.', post id: '.$params['postId'];
    }
}