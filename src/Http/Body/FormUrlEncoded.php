<?php

namespace PlugRoute\Http\Body;

use PlugRoute\Http\Globals\Server;

class FormUrlEncoded implements Handler, Advancer
{
	private Handler $handler;

	private array $data;

	private const CONTENT_TYPE = 'x-www-form-urlencoded';

	public function __construct()
    {
        $this->data = [];
    }

    public function getBody($content): array
	{
	    if (is_array($content)) {
	        return $content;
        }

	    $this->handleSentData($content);

	    return $this->data;
	}

    private function handleSentData(string $content): void
    {
        $body = explode('&', $content);

        foreach ($body as $parameter) {
            $parameterParsed = explode('=', $parameter);

            $this->data[$parameterParsed[0]] = str_replace("%0A", "\n", $parameterParsed[1]);
        }
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