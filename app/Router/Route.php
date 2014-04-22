<?php

namespace QDE\Router;

use \QDE\Request;

class WrongRouteException extends \Exception
{
}

class Route
{
    private $controller = null;
    private $action = null;
    private $route = null;
    private $regexPattern = null;
    private $variables = null;

    public function __construct($route, $controller, $action, $variables=null)
    {
        $this->setRoute($route);
        $this->setController($controller);
        $this->setAction($action);
        $this->setRegexPatternFromRoute($route, $variables);
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function setRegexPattern($pattern)
    {
        $this->regexPattern = $pattern;
    }

    public function getRegexPattern()
    {
        return $this->regexPattern;
    }

    public function setRegexPatternFromRoute($route, $variables)
    {
        if ($variables !== null)
        {
            foreach($variables as $key => $pattern)
            {
                $route = str_replace('{'.$key.'}', '(?<'. $key .'>'. $pattern .')', $route);
            }
        }
        $this->setRegexPattern('#^'.$route.'$#U');
    }

    public function setController($controller)
    {
        $this->controller = 'Becee\\Controllers\\' .$controller;
    }

    public function setAction($action)
    {
        $this->action = $action . 'Action';
    }

    public function is_url_for_this_route($url)
    {
        if (preg_match($this->regexPattern, $url) === 1)
            return true;
        else
            return false;
    }

    public function callController($url)
    {
        if ($this->is_url_for_this_route($url) === true)
        {
            $request = new Request();
            $request->setParamsUri($this->parse_params($url));
            $controller = new $this->controller;
            echo call_user_func(array($controller, $this->action), $request);
        }
        else
        {
            throw new WrongRouteException('Url does not match');
        }
    }

    public function parse_params($url)
    {
        preg_match($this->regexPattern, $url, $matches);
        return array_slice($matches, 1);
    }
}
