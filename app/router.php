<?php

class Router
{
    private $routes = array();
    private $actions = array();

    public function __construct()
    {
        $this->routes = array();
        $this->actions = array();
    }

    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }

    public function addRoutes(array $routes)
    {
        foreach($routes as $route)
        {
            $this->addRoute($route);
        }
    }

    public function getPage($url)
    {
        foreach($this->routes as $route)
        {
            $found = true;
            try
            {
                $data = $route->callController($url);
            }
            catch(Exception $e)
            {
                $found = false;
            }
            if ($found === true)
                break;
        }
        if ($found === true)
        {
            return $data;
        }
        else
        {
            header('HTTP/1.0 404 Not Found');
        }
    }
}

class Route
{
    private $controller = null;
    private $action = null;
    private $route = null;

    public function __construct($route, $controller, $action)
    {
        $this->setRoute($route);
        $this->setController($controller);
        $this->setAction($action);
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function setAction($action)
    {
        $this->action = $action . 'Action';
    }

    public function is_url_for_this_route($url)
    {
        if (preg_match($this->route, $url) === 1)
            return true;
        else
            return false;
    }

    public function callController($url)
    {
        if ($this->is_url_for_this_route($url) === true)
        {
            $params = array(new Request());
            $params = array_merge($params, $this->parse_params($url));
            $controller = new $this->controller;
            return call_user_func_array(array($controller, $this->action), $params);
        }
        else
        {
            throw new Exception();
        }
    }

    public function parse_params($url)
    {
        preg_match($this->route, $url, $matches);
        return array_slice($matches, 1);
    }
}

class Request
{}
