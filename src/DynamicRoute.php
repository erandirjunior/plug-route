<?php

namespace PlugRoute;

use PlugRoute\Helpers\MatchHelper;

class DynamicRoute extends RouteAnalyzer
{
    private $matches;

	private $indentifiers;

    protected function checkIfCanHandlerRoute(string $route, string $url)
    {
        $pattern        = '({.+?(?:\:.*?)?})';
        $this->matches  = MatchHelper::getMatchAll($route, $pattern, 0);

        return $this->matches;
    }

    /**
     * Replace all dynamic values by regex.
     * @see organizeMatches
     * @see replace
     *
     * set route
     * @see setRoute
     *
     * set dynamic values.
     * @see getDynamicValues
     *
     * @param $route
     * @param $url
     * @param $matches
     */
    protected function routeHandler(string $route, string $url)
    {
        $this->indentifiers = [];
        $this->route        = $route;
        $organizedMatches   = $this->organizeMatches($this->matches);
        $route              = $this->prepareRoute($route, $organizedMatches);
        $matchCase          = MatchHelper::getMatchCase($url, $route);

        $this->setRouteAndDynamicValuesIfMatchCase($matchCase);
    }

    private function organizeMatches($matches)
    {
        $matchesOrganized = $this->getArrayMatch();

        foreach ($matches as $value) {
            $valueWithoutKeys       = str_replace(['{', '}'], '', $value);
            $arrayMatch             = explode(':', $valueWithoutKeys);
            $this->indentifiers[]   = $arrayMatch[0];
            count($arrayMatch) > 1
            ? $this->setRegex($matchesOrganized, $value, $arrayMatch[1])
            : $this->getRegexForValuesWithoutRegex($matchesOrganized, $value);
        }

        return $matchesOrganized;
	}

    protected function prepareRoute(string $route, $organizedMatches)
    {
        $route = $this->replace('regex', $organizedMatches, $route);
        $route = $this->replace('all', $organizedMatches, $route);
        $route = $this->replace('optional', $organizedMatches, $route);
        $route = str_replace('/', '\/', $route);

        return $route;
    }

    private function replace($key, $data, $route)
    {
        return str_replace($data[$key]['value'], $data[$key]['match'], $route);
    }

    private function setRoute(&$matchCase)
    {
        $this->route = array_shift($matchCase);
    }

    private function getDynamicValues($matchCase): void
    {
        foreach ($this->indentifiers as $key => $value) {
            $this->parameters[$value] = $matchCase[$key];
        }
    }

    private function getRegexForValuesWithoutRegex(&$matchesOrganized, $value)
    {
        $lengthHaystack = strstr($this->route, $value);

        $matchesOrganized['all']['value'][] = $value;
        $matchesOrganized['all']['match'][] = strlen($lengthHaystack) > strlen($value) ? '(.+?)' : '(.+)';
    }

    private function setRegex(&$matchesOrganized, $value, $match)
    {
        if ($match !== '?') {
            return $this->setRegexIfValueHasRegex($matchesOrganized, $value, $match);;
        }

        $matchesOrganized['optional']['value'][] = $value;
        $matchesOrganized['optional']['match'][] = '((?:.+)?)';
    }

    private function setRegexIfValueHasRegex(&$matchesOrganized, $value, $match)
    {
        $matchesOrganized['regex']['value'][] = $value;
        $matchesOrganized['regex']['match'][] = "({$match})";
    }

    private function getArrayMatch()
    {
        foreach (['optional', 'regex', 'all'] as $value) {
            $matchesOrganized[$value] = [
                'match' => [],
                'value' => [],
            ];
        }

        return $matchesOrganized;
    }

    protected function setRouteAndDynamicValuesIfMatchCase($matchCase)
    {
        if ($matchCase) {
            $this->setRoute($matchCase);
            $this->getDynamicValues($matchCase);
        }
    }
}