<?php

namespace QDE;


require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once 'config.php';

use \QDE\Router\Router;


class App
{
    protected $router = null;
    protected $twig = null;
    protected $routes_root = null;

    public function __construct()
    {
        $this->router = new Router();
        $this->config = \get_config();
        $this->router->addRoutesFromJsonFile('routes.json');
        $loader = new \Twig_Loader_Filesystem('../src/tpl');
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => '../cache/tpl',
            'debug' => \get_config()['debug']));

        if (strpos($_SERVER['REQUEST_URI'], 'app/app.php/') !== false)
            $this->routes_root = explode('app/app.php/', $_SERVER['REQUEST_URI'], 2)[0] . 'app/app.php/';
        else
            $this->routes_root = '/' . $this->config['server_root']. '/';

        $this->addTwigFunctions();

    }

    public function run()
    {
        $path = explode($this->routes_root, $_SERVER['REQUEST_URI'], 2)[1];
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

    public function getManager($name)
    {
        $managerName = 'Becee\\Models\\'.ucfirst($name).'Manager';
        return new $managerName($this);
    }

    protected function addTwigFunctions()
    {
        $path_function = new \Twig_SimpleFunction('path', function ($name, $args=null) {
            return $this->routes_root . $this->router->getUrl($name, $args);
        });
        $media_function = new \Twig_SimpleFunction('media', function ($url) {
            return '/' . $this->config['server_root'] . '/media/' . $url;
        });

        $this->twig->addFunction($path_function);
        $this->twig->addFunction($media_function);
    }
}

$app = new App();
$app->run();
