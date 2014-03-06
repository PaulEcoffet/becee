<?php

require_once dirname(__FILE__).'/route.php';
require_once dirname(dirname(__FILE__)).'/request.php';

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
        if ($found === false)
        {
            header('HTTP/1.0 404 Not Found');
        }
    }
}
