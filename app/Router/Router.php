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
        $this->error404Route = new Route('404Error', '{url}', 'HttpError', 'Error404', array('url' => '.*'));
    }

    public function addRoute(Route $route)
    {
        $this->routes[$route->getName()] = $route;
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
                $variables = (array) $route->variables;
            }

            $route_obj = new Route($route->name, $route->route, $route->controller, $route->action, $variables);
            $this->addRoute($route_obj);
        }
    }

    public function getRoute($url)
    {
        $found = false;
        foreach($this->routes as $route)
        {
            if($route->is_url_for_this_route($url))
                return $route;
        }
        return $this->error404Route;
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

    public function getUrl($name, $args)
    {
        if(!isset($this->routes[$name]))
        {
            throw Exception('This route doesn\'t exist');
        }
        $route = $this->routes[$name]->getRoute();
        $variables = $this->routes[$name]->getVariablesList();
        if ($variables !== null)
        {
            foreach($variables as $key)
            {
                $value = isset($args[$key])?$args[$key]:'';
                $route = str_replace('{'.$key.'}', $value, $route);
            }
        }
        return $route;
    }
}
