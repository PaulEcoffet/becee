<?php

require 'router.php';

class Dummy
{
    public function viewAction()
    {
        return "Hello";
    }
}

$router = new Router();
$route = new Route('/^lol$/', "Dummy", "view");

$router->addRoute($route);


print_r($router->getPage($_SERVER['QUERY_STRING']));
