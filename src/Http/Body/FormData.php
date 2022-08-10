<?php

namespace PlugRoute\Http\Body;

use PlugRoute\Http\Globals\Server;

class FormData implements Handler, Advancer
{
	private Handler $handler;

	private array $currentData;

    private const CONTENT_TYPE = 'form-data';

	public function __construct()
    {
        $this->currentData = [];
    }

    public function getBody($content): array
	{
        $boundary = substr($content, 0, strpos($content, "\r\n"));
        $parts = array_slice(explode($boundary, $content), 1);

        foreach ($parts as $part) {
            preg_match_all('/"(.+)"+\s+([^\t]+)/', $part, $matches);

            $this->handleBodySent($matches);
        }

        return $this->currentData;
	}

    private function handleBodySent(array $matches): void
    {
        foreach ($matches[1] as $key => $match) {
            $matchKey = str_replace(["'", '"'], '', $match);

            $this->currentData[$matchKey] = substr($matches[2][$key], 0, -2);
        }
	}

	public function next(Handler $handler)
	{
		$this->handler = $handler;
	}

	private function isFormDataContentType(Server $server): bool
	{
		return $server->contentTypeIs(self::CONTENT_TYPE) && $server->method() !== 'POST';
	}

	public function handle(Server $server): array
	{
		if ($this->isFormDataContentType($server)) {
			return $this->getBody($server->getContent());
		}

		return $this->handler->handle($server);
	}
}