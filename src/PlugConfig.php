<?php
/**
 * Created by PhpStorm.
 * User: erandir
 * Date: 25/07/18
 * Time: 19:12
 */

namespace PlugRoute;


use PlugRoute\Helper\PlugHelper;

// TODO: check class
class PlugConfig
{
    /**
     * Receive routes.
     *
     * @var array
     */
    private $routes;

    private $piecesUrl;

    private $urlError;

    private $matches;

    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }

    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param string $url
     * @see isDynamicRoute
     * @see processRoutes
     * @see execute
     * @see countError
     */
    private function index()
    {
        try {
            $url = PlugHelper::getUrlPath();
            $this->routes = PlugHelper::filter($this->routes);
            array_walk($this->routes, function ($route) use ($url) {
                if ($this->isDynamic($route['route'])) {
                    $this->processRoutes($route, $url);
                } else {
                    $this->execute($url, $route);
                }
                $this->countError($this->routes);
            });
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function isDynamic($route)
    {
        preg_match_all('({.+?}/?)', $route, $this->matches);
        return count($this->matches[0]) > 0 ? true : false;
    }

    private function processRoutes($route, $url)
    {
        $route['route'] = str_replace(['{', '}'], '', $route['route']);
        $urlArray = explode('/', $url);
        $routeArray = explode('/', $route['route']);

        $this->matches = $this->cleanMatch($this->matches);
        $indice = $this->getIndex($routeArray);

        foreach ($routeArray as $k => $v) {
            if (empty($urlArray[$k])) {
                $urlArray[$k] = '';
            }

            if ($v != $urlArray[$k] && $indice[$k] == $v) {
                $urlValues[] = $urlArray[$k];
                $this->piecesUrl[] = $urlArray[$k];
                continue;
            }

            $urlValues[] = $v;
        }

        $route['route'] = implode('/', $urlValues);

        $this->execute($url, $route);
    }

    private function cleanMatch($matches)
    {
        // TODO: move method to helper
        foreach ($matches[0] as $k => $v) {
            $matches[$k] = str_replace(['/{', '{', '}', '}/', '/'], '', $v);
        }

        return $matches;
    }

    private function getIndex($routeArray)
    {
        $matches = $this->matches;
        array_walk($routeArray, function ($k, $v) use ($matches, &$indice) {
            foreach ($matches as $j => $value) {
                if ($k == $value) {
                    $indice[$v] = $k;
                }
            }
        });

        return $indice;
    }

    private function execute(string $url, array $route)
    {
        if ($url === $route['route']) {
            if (is_callable($route['callback'])) {
                return $route['callback']($this->piecesUrl);
            }

            $callback = explode("@", $route['callback']);
            $class = $callback[0];
            $method = $callback[1];
            $instance = $this->createInstance($class);

            return $this->action($instance, $method);
        }

        $this->urlError++;
    }

    public function run()
    {
        $this->index();
    }

    private function createInstance($class)
    {
        $class = $this->namespace.ucfirst($class);

        if (!class_exists($class)) {
            throw new \Exception("Error: class {$class} does not exist. Check the name of your ");
        }

        $instance = new $class;
        return $instance;
    }

    private function action($instance, $method)
    {
        if (method_exists($instance, $method)) {
            return $instance->$method($this->piecesUrl);
        }
        throw new \Exception("Error: method $method does not exist.");
    }

    private function countError($value)
    {
        if (count($value) == $this->urlError) {
            throw new \Exception("Error: route does not exist");
        }
    }
}