<?php

require_once 'router/router.php';
require_once 'config.php';
require_once 'routes.php';

$router = new Router();

$router->addRoutes(get_routes());

$path = explode('?', $_SERVER['QUERY_STRING'])[0];
$router->getPage($path);
