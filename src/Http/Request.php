<?php

namespace PlugRoute\Http;

use PlugRoute\Cache;
use PlugRoute\Error;
use PlugRoute\Http\Body\Content;
use PlugRoute\Http\Globals\Cookie;
use PlugRoute\Http\Globals\Env;
use PlugRoute\Http\Globals\File;
use PlugRoute\Http\Globals\Get;
use PlugRoute\Http\Globals\Server;
use PlugRoute\Http\Globals\Session;
use PlugRoute\Http\Utils\ArrayUtil;
use stdClass;

class Request
{
    private Get $get;

    private Env $env;

    private File $file;

    private Cookie $cookie;

    private Session $session;

    private Server $server;

    private Content $content;

    private array $parameter;

    private array $routeNamed;

    public function __construct()
    {
        $this->get = new Get();
        $this->env = new Env();
        $this->file = new File();
        $this->cookie = new Cookie();
        $this->session = new Session();
        $this->server = new Server();
        $this->content = new Content($this->server);
        $this->parameter = [];
        $this->routeNamed = [];
    }

    public function addCookie(string $key, $value, $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false): void
    {
        $this->cookie->add($key, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public function cookies(?string $key = null)
    {
        if ($key) {
            return $this->cookie->get($key);
        }

        return $this->cookie->all();
    }

    public function hasCookie($key): bool
    {
        return $this->cookie->has($key);
    }

    public function removeCookie($key): void
    {
        $this->cookie->remove($key);
    }

    public function exceptCookies(array $values): array
    {
        return $this->cookie->except($values);
    }

    public function onlyCookies(array $values): array
    {
        return $this->cookie->only($values);
    }

    public function query(?string $key = null)
    {
        if ($key) {
            return $this->get->get($key);
        }

        return $this->get->all();
    }

    public function addQuery(string $key, $value): void
    {
        $this->get->add($key, $value);
    }

    public function onlyQueries(array $values): array
    {
        return $this->get->only($values);
    }

    public function exceptQueries(array $values): array
    {
        return $this->get->except($values);
    }

    public function hasQuery(string $value): bool
    {
        return $this->get->has($value);
    }

    public function removeQuery(string $key): void
    {
        $this->get->remove($key);
    }

    public function env(?string $key = null)
    {
        if ($key) {
            return $this->env->get($key);
        }

        return $this->env->all();
    }

    public function addEnv(string $key, $value): void
    {
        $this->env->add($key, $value);
    }

    public function onlyEnv(array $values): array
    {
        return $this->env->only($values);
    }

    public function exceptEnv(array $values): array
    {
        return $this->env->except($values);
    }

    public function hasEnv(string $value): bool
    {
        return $this->env->has($value);
    }

    public function removeEnv(string $key): void
    {
        $this->env->remove($key);
    }

    public function files(?string $key = null)
    {
        if ($key) {
            return $this->file->get($key);
        }

        return $this->file->all();
    }

    public function hasFile($key): bool
    {
        return $this->file->has($key);
    }

    public function removeFile($key): void
    {
        $this->file->remove($key);
    }

    public function exceptFiles(array $values): array
    {
        return $this->file->except($values);
    }

    public function onlyFiles(array $values): array
    {
        return $this->file->only($values);
    }

    public function addHeader(string $key, $value): void
    {
        $this->server->add($key, $value);
    }

    public function headers(?string $key = null)
    {
        if ($key) {
            return $this->server->get($key);
        }

        return $this->server->all();
    }

    public function hasHeader($key): bool
    {
        return $this->server->has($key);
    }

    public function removeHeader($key): void
    {
        $this->server->remove($key);
    }

    public function exceptHeaders(array $values): array
    {
        return $this->server->except($values);
    }

    public function onlyHeaders(array $values): array
    {
        return $this->server->only($values);
    }

    public function method(): string
    {
        return $this->server->method();
    }

    public function getUrl(): string
    {
        return $this->server->getUrl();
    }

    public function isMethod(string $method): bool
    {
        return $this->method() === strtoupper($method);
    }

    public function redirect(string $path, int $code = 301)
    {
        header('HTTP/1.0 '.$code);
        header('Location: '.$path);

        return true;
    }

    public function addSession(string $key, $value): void
    {
        $this->session->add($key, $value);
    }

    public function session(?string $key = null)
    {
        if ($key) {
            return $this->session->get($key);
        }

        return $this->session->all();
    }

    public function hasSession($key): bool
    {
        return $this->session->has($key);
    }

    public function removeSession($key): void
    {
        $this->session->remove($key);
    }

    public function exceptSession(array $values): array
    {
        return $this->session->except($values);
    }

    public function onlySession(array $values): array
    {
        return $this->session->only($values);
    }

    public function except(array $values)
    {
        return $this->content->except($values);
    }

    public function only(array $values)
    {
        return $this->content->only($values);
    }

    public function has(string $key): bool
    {
        return $this->content->has($key);
    }

    public function remove(string $key): void
    {
        $this->content->remove($key);
    }

    public function add($key, $value)
    {
        $this->content->add($key, $value);
    }

    public function all()
    {
        $content = $this->content->all();

        if (is_array($content)) {
            return $content;
        }

        return [$content];
    }

    public function bodyAsObject(): stdClass
    {
        $data = $this->content->all();

        if (is_array($data)) {
            return ArrayUtil::convertToObject($this->content->all());
        }

        Error::throwException("It wasn't possible convert to object");
    }

    public function get(string $value)
    {
        return $this->content->get($value);
    }

    public function __get($name)
    {
        return $this->content->$name;
    }

    public function parameter($key)
    {
        return $this->parameter[$key];
    }

    public function parameters(): array
    {
        return $this->parameter;
    }

    public function addParameter($key, $value): Request
    {
        $this->parameter[$key] = $value;

        return $this;
    }

    public function addParameters(array $data): Request
    {
        foreach ($data as $key => $value) {
            $this->parameter[$key] = $value;
        }

        return $this;
    }

    public function redirectToRoute(string $name, int $code = 301)
    {
        $route = Cache::get($name);

        if (empty($route)) {
            Error::throwException("Name wasn't defined.");
        }

        return $this->redirect($route, $code);
    }

    public function setRouteNamed(array $routeNamed): Request
    {
        $this->routeNamed = $routeNamed;

        return $this;
    }

    public function getAllRouteNamed(): array
    {
        return $this->routeNamed;
    }
}