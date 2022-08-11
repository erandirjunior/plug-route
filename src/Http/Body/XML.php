<?php

namespace PlugRoute\Http\Body;

use PlugRoute\Http\Globals\Server;

class XML implements Handler, Advancer
{
    private Handler $handler;

    public function getBody($content): array
    {
        $xml = simplexml_load_string($content);
        $json = json_encode($xml);

        return json_decode($json, true);
    }

    public function next(Handler $handler): void
    {
        $this->handler = $handler;
    }

    private function isTextXml(Server $server): bool
    {
        return $server->contentTypeIs('text/xml');
    }

    private function isApplicationXml(Server $server): bool
    {
        return $server->contentTypeIs('application/xml');
    }

    public function handle($server): array
    {
        $isTextXML = $this->isTextXml($server);
        $isApplicationXML = $this->isApplicationXml($server);

        if ($isTextXML || $isApplicationXML) {
            return $this->getBody($server->getContent());
        }

        return $this->handler->handle($server);
    }
}