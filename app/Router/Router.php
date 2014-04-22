<?php

namespace QDE\Router;

use \QDE\Request;
use \QDE\Router\Route;


class Router
{
    protected $routes = array();
    protected $actions = array();
    protected $error404Route = null;

    public function __construct()
    {
        $this->routes = array();
        $this->actions = array();
        $this->error404Route = new Route('{url}', 'HttpError', 'Error404', array('url' => '.*'));
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

    public function addRoutesFromJsonFile($path)
    {
        $routes = \json_decode(\utf8_encode(\file_get_contents($path)));
        foreach($routes as $route)
        {
            $variables = null;
            if (isset($route->variables))
            {
                $variables = $route->variables;
            }

            $route_obj = new Route($route->route, $route->controller, $route->action, $variables);
            $this->addRoute($route_obj);
        }
    }

    public function getPage($url)
    {
        $found = false;
        foreach($this->routes as $route)
        {
            $found = true;
            try
            {
                $data = $route->callController($url);
            }
            catch(WrongRouteException $e)
            {
                $found = false;
            }
            if ($found === true)
                break;
        }
        if ($found === false)
        {
            header('HTTP/1.0 404 Not Found');
            $this->error404Route->callController($url);
        }
    }

    public function getRouteByPattern($pattern)
    {
        foreach($this->routes as &$route)
        {
            if($route->getRoute() === $pattern)
            return $route;
        }
        throw Exception('There is no route with the pattern \''. $pattern .'\' registered');
    }
}
