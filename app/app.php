<?php

require 'router/router.php';

class Dummy
{
    public function viewAction($request)
    {
        $request->parseTemplate('test.html.link', array('nb' => 2));
    }
}

$router = new Router();
$route = new Route('lol', "Dummy", "view");

$router->addRoute($route);
$path = explode('?', $_SERVER['QUERY_STRING'])[0];
$router->getPage($path);
