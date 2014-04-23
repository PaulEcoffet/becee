<?php

namespace QDE;


require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once 'config.php';

use \QDE\Router\Router;


class App
{
    protected $router = null;
    protected $twig = null;

    public function __construct()
    {
        $this->router = new Router();
        $this->router->addRoutesFromJsonFile('routes.json');
        $loader = new \Twig_Loader_Filesystem('../src/tpl');
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => '../cache/tpl',
            'debug' => \get_config()['debug']));
    }

    public function run()
    {
        $path = explode('?', $_SERVER['QUERY_STRING'])[0];
        $route = $this->router->getRoute($path);
        $request = new Request($this);
        $request->setParamsUri($route->parse_params($path));
        $controller_str = $route->getController();
        $controller = new $controller_str();
        echo call_user_func(array($controller, $route->getAction()), $request);
    }

    public function getTwig()
    {
        return $this->twig;
    }
}

$app = new App();
$app->run();
