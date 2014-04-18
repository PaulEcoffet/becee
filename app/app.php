<?php

require_once 'router/router.php';
require_once 'config.php';

$router = new Router();

$router->addRoutesFromJson('routes.json');

$path = explode('?', $_SERVER['QUERY_STRING'])[0];
$router->getPage($path);
