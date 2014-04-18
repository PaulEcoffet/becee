<?php

require_once dirname(__FILE__).'/route.php';
require_once dirname(dirname(__FILE__)).'/request.php';

class Router
{
    protected $routes = array();
    protected $actions = array();
    protected $error404Route = null;

    public function __construct()
    {
        $this->routes = array();
        $this->actions = array();
        $this->error404Route = new Route('(.*)', 'HttpError', 'Error404');
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

    public function addRoutesFromJson($path)
    {
        $routes = json_decode(utf8_encode(file_get_contents($path)));
        foreach($routes as $route)
        {
            $this->addRoute(new Route($route->route, $route->controller, $route->action));
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
