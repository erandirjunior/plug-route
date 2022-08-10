<?php

namespace PlugRoute\Http\Body;

use PlugRoute\Http\Globals\Post;
use PlugRoute\Http\Globals\Server;
use PlugRoute\Http\Utils\ArrayUtil;

class Content
{
	private Server $server;

    /**
     * @var string|array
     */
	private $requestBody;

	public function __construct(Server $server)
	{
		$this->server = $server;
		$this->getBodyRequest();
        $this->createProperty();
	}

	private function getBodyRequest()
	{
        $xml = new XML();
        $json = new Json();
        $post = new Post();
        $formData = new FormData();
        $urlEncode = new FormUrlEncoded();
        $textPlain = new TextPlain();

		$json->next($formData);
		$formData->next($urlEncode);
		$urlEncode->next($textPlain);
		$textPlain->next($xml);
		$xml->next($post);

		$this->requestBody = $json->handle($this->server);
	}

	private function createProperty()
	{
        if (is_array($this->requestBody)) {
            foreach ($this->requestBody as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public function add(string $key, $value): void
    {
        $this->requestBody[$key] = $value;

        $this->__set($key, $value);
    }

    public function remove(string $key): void
    {
        unset($this->requestBody[$key]);

        unset($this->$key);
    }

    public function get(string $key)
    {
        return $this->requestBody[$key];
    }

    /**
     * @return array|string
     */
    public function all()
    {
        return $this->requestBody;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        $this->$name;
    }

    public function except(array $keys): array
    {
        return ArrayUtil::except($this->requestBody, $keys);
    }

    public function only(array $keys): array
    {
        return ArrayUtil::only($this->requestBody, $keys);
    }

    public function has(string $key): bool
    {
        return !empty($this->requestBody[$key]);
    }
}