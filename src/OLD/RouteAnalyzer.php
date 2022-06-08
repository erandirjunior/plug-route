<?php

namespace PlugRoute;

use PlugRoute\Helpers\MatchHelper;

class RouteAnalyzer
{
    private $parameters;

    private $matchCase;

    private $route;

    public function __construct()
    {
        $this->parameters = [];
        $this->matchCase = [];
    }

    public function getRoute(string $route, string $url): string
    {
        $this->resetParameters();
        $this->setRegExpDefinedInRoute($route);

        if (empty($this->parameters)) {
            return $this->route;
        }

        return $this->routeHandler($url);
    }

    public function getParameters(): array
    {
        $parameters = [];

        foreach ($this->parameters as $key => $value) {
            $parameters[$value] = $this->matchCase[$key];
        }

        return $parameters;
    }

    private function routeHandler(string $url): string
    {
        $this->scapeSpecialChars();
        $this->setMatchCase($url);
        $this->setRoute();
        return $this->route;
    }

    private function resetParameters(): void
    {
        $this->parameters = [];
    }

    private function setRegExpDefinedInRoute(string $route): void
    {
        $pattern = '/{(.+?(?:\:.*?)?)}/';
        $this->route = preg_replace_callback($pattern, function ($matches)
        {
            $matchParsed = explode(':', $matches[1]);
            $this->parameters[] = array_shift($matchParsed);
            $defaultMatch = '(.+)';

            if (!$matchParsed) {
                return $defaultMatch;
            }

            return $this->getMatchExpression($defaultMatch, $matchParsed[0]);
        }, $route);
    }

    private function getMatchExpression($pattern, $match): string
    {
        return $match === '?' ? $pattern.'?' : "({$match})";
    }

    private function scapeSpecialChars(): void
    {
        $this->route = preg_replace('/(\/)/', '\/', $this->route);
    }

    private function setMatchCase($url): void
    {
        $this->matchCase = MatchHelper::getMatchCase($url, $this->route);
    }

    private function setRoute(): void
    {
        if ($this->matchCase) {
            $this->route = array_shift($this->matchCase);
        }
    }
}