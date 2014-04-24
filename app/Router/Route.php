<?php

namespace QDE\Router;

use \QDE\Request;

class WrongRouteException extends \Exception
{
}

class Route
{
    private $name = null;
    private $controller = null;
    private $action = null;
    private $route = null;
    private $regexPattern = null;
    private $variablesList = null;

    public function __construct($name, $route, $controller, $action, $variables=null)
    {
        $this->setName($name);
        $this->setRoute($route);
        $this->setController($controller);
        $this->setAction($action);
        if(is_array($variables))
        {
            $this->setVariablesList(array_keys($variables));
        }
        $this->setRegexPatternFromRoute($route, $variables);
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
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

    public function getController()
    {
        return $this->controller;
    }

    public function setAction($action)
    {
        $this->action = $action . 'Action';
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setVariablesList(Array $variablesList)
    {
        $this->variablesList = $variablesList;
    }

    public function getVariablesList()
    {
        return $this->variablesList;
    }

    public function is_url_for_this_route($url)
    {
        if (preg_match($this->regexPattern, $url) === 1)
            return true;
        else
            return false;
    }

    public function parse_params($url)
    {
        preg_match($this->regexPattern, $url, $matches);
        return array_slice($matches, 1);
    }
}
