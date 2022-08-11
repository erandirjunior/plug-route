<?php

namespace PlugRoute\Http\Body;

use PlugRoute\Http\Globals\Server;

class TextPlain implements Handler, Advancer
{
    private Handler $handler;

    private const CONTENT_TYPE = 'text/plain';

    public function getBody($content): array
    {
        return [$content];
    }

    public function next(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(Server $server): array
    {
        if ($server->contentTypeIs(self::CONTENT_TYPE)) {
            return $this->getBody($server->getContent());
        }

        return $this->handler->handle($server);
    }
}