<?php

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
            call_user_func_array(array($controller, $this->action), $params);
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
