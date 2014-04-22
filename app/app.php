<?php

namespace QDE;


require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once 'config.php';

use \QDE\Router\Router;

$router = new Router();

$router->addRoutesFromJsonFile('routes.json');

$path = explode('?', $_SERVER['QUERY_STRING'])[0];
$router->getPage($path);
