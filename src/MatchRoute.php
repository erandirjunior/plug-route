<?php

namespace PlugRoute;

class MatchRoute
{
    private string $url;

    private string $urlCurrentPattern;

    private array $parameters;

    private string $route;

    private array $dynamicParametersInRoute;

    public function __construct(string $url)
    {
        $this->url = $url;
        $this->urlCurrentPattern = '';
        $this->parameters = [];
        $this->dynamicParametersInRoute = [];
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function urlAndRouteAreEqual(Route $route): bool
    {
        $this->parameters = [];
        $this->route = $route->getRoute();
        $this->mountUrlExpressionPattern($route);
        return $this->hasMatch();
    }

    private function hasMatch(): bool
    {
        preg_match("/^$this->urlCurrentPattern$/", $this->url, $match);
        $this->setParameters($match);
        return (bool) $match;
    }

    private function setParameters(?array $match = [])
    {
        array_shift($match);

        foreach ($this->dynamicParametersInRoute as $key => $matchCase) {
            $position = $matchCase[1];
            $this->parameters[$position] = $match[$key] ?? null;
        }
    }

    public function mountUrlExpressionPattern(Route $route): void
    {
        $this->urlCurrentPattern = str_replace('/', '\/', $route->getRoute());
        $this->dynamicParametersInRoute = $this->getAllDynamicParameters();
        $rules = $route->getRules();

        foreach ($this->dynamicParametersInRoute as $value) {
            $this->setExpression($rules, $value);
        }
    }

    private function getAllDynamicParameters(): array
    {
        $patternOfAllRulesInCurrentUrl = '/{(.+?)(\?)?}/';
        preg_match_all($patternOfAllRulesInCurrentUrl, $this->urlCurrentPattern, $matches, PREG_SET_ORDER);
        return $matches ?? [];
    }

    private function setExpression($rules, $value): void
    {
        $pattern = $rules[$value[1]] ?? null;

        if ($pattern) {
            $this->setExpressionAssigned($pattern, $value);
            return;
        }

        $this->setGenericExpression($value);
    }

    private function setExpressionAssigned($pattern, $value): void
    {
        $this->setPatternInCurrentUrl($value, "($pattern)");
    }

    private function setGenericExpression($value): void
    {
        $pattern = '([^\s\/]+)';

        if ($this->hasPreviousPattern($value[0]) || isset($value[2])) {
            $pattern .= '?';
        }

        $this->setPatternInCurrentUrl($value, $pattern);
    }

    private function hasPreviousPattern(string $value): bool
    {
        $strPreviousCurrentPattern = strstr($this->route, $value, true);
        preg_match('/({.+})$/', $strPreviousCurrentPattern, $hasPreviousPattern);
        return (bool) $hasPreviousPattern;
    }

    private function setPatternInCurrentUrl($value, string $currentPattern): void
    {
        $this->urlCurrentPattern = str_replace($value[0], $currentPattern, $this->urlCurrentPattern);
    }
}