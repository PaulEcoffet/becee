<?php

namespace QDE;


require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once 'config.php';

use \QDE\Router\Router;


class App
{
    protected $router = null;
    protected $config = null;
    protected $twig = null;
    protected $routes_root = null;
    protected $becee_root = null;
    protected $route_name = null;
    protected $db_connection;
    protected $geocoder;
    protected $managers = array();
    protected $hooks = array();

    public function __construct()
    {
        // PHP SPECIFIC
        session_start();
        date_default_timezone_set('Europe/Paris');
        setlocale(LC_CTYPE, 'fr_FR');

        //Setting the config
        $this->becee_root = realpath(dirname(__FILE__).'/../');
        $this->config = \get_config();

        //Setting up the router
        $this->router = new Router();
        $this->router->addRoutesFromJsonFile('routes.json');

        //Setting up the template engine
        $loader = new \Twig_Loader_Filesystem('../src/tpl');
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => '../cache/tpl',
            'debug' => \get_config()['debug']));

        //Determine if url_rewritting is set and define how routes should behave
        if (strpos($_SERVER['REQUEST_URI'], 'app/app.php/') !== false)
            $this->routes_root = explode('app/app.php/', $_SERVER['REQUEST_URI'], 2)[0] . 'app/app.php/';
        else
            $this->routes_root = '/' . $this->config['server_root']. '/';

        $this->addTwigFunctions();

        $this->createPdoConnection();

        $this->createGeocoder();
    }

    public function run()
    {
        $path = explode($this->routes_root, $_SERVER['REQUEST_URI'], 2)[1];
        $route = $this->router->getRoute($path);
        $this->setRouteName($route->getName());
        $request = new Request($this);
        $request->setParamsUri($route->parse_params($path));
        $controller_str = $route->getController();
        $controller = new $controller_str();
        foreach($this->hooks as $hook)
        {
            $request = $hook->runAscending($request);
        }
        $response = call_user_func(array($controller, $route->getAction()), $request);
        foreach($this->hooks as $hook)
        {
            $response = $hook->runDescending($response);
        }
        echo $response->run($this);
    }

    public function getTwig()
    {
        return $this->twig;
    }

    public function getManager($name)
    {
        if (array_key_exists($name, $this->managers) === false)
        {
            $managerName = 'Becee\\Models\\'.ucfirst($name).'Manager';
            $this->managers[$name] = new $managerName($this);
        }
        return $this->managers[$name];
    }

    public function getBeceeRoot()
    {
        return $this->becee_root;
    }

    public function getMediaPath()
    {
        return realpath($this->becee_root.'/media/');
    }

    public function getCachePath()
    {
        return realpath($this->becee_root.'/cache/');
    }

    public function getTmpPath()
    {
        return realpath($this->becee_root.'/tmp/');
    }

    public function getPdo()
    {
        return $this->db_connection;
    }

    public function setSession($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function getSession($name)
    {
        if (isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
        else
        {
            throw new \Exception("Non existing session"); // TODO Custom exception
        }
    }

    public function deleteSession($name)
    {
        unset($_SESSION[$name]);
    }

    public function hasSession($name)
    {
        return (array_key_exists($name, $_SESSION));
    }

    public function setCookie($name, $value, $expiration)
    {
        setcookie($name, $value, $expiration);
    }

    public function getCookie($name)
    {
        return $_COOKIE[$name];
    }

    public function hasCookie($name)
    {
        return array_key_exists($name, $_COOKIE);
    }

    public function deleteCookie($name)
    {
        unset($_COOKIE[$name]);
        setcookie($name, '', time()-3600*24*366);
    }

    public function getGeocoder()
    {
        return $this->geocoder;
    }

    public function getClientIp()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function addHook(\QDE\Hook $hook)
    {
        $this->hooks[$hook->getName()] = $hook;
    }

    public function getHook($hookname)
    {
        return $this->hooks[$hookname];
    }

    public function removeHook($hookname)
    {
        unset($this->hooks[$hookname]);
    }

    public function getPath($name, $args=null)
    {
        return $this->routes_root . $this->router->getUrl($name, $args);
    }

    public function setHeader($data)
    {
        header($data);
    }

    public function setRouteName($routename)
    {
        $this->route_name = $routename;
    }

    public function getRouteName()
    {
        return $this->route_name;
    }

    protected function addTwigFunctions()
    {
        $path_function = new \Twig_SimpleFunction('path', function ($name, $args=null) {
            return $this->getPath($name, $args);
        });
        $media_function = new \Twig_SimpleFunction('media', function ($url) {
            return '/' . $this->config['server_root'] . '/media/' . $url;
        });

        $this->twig->addFunction($path_function);
        $this->twig->addFunction($media_function);
    }

    protected function createPdoConnection()
    {
        try
        {
            $this->db_connection = new \PDO('mysql:host='. $this->config['mysql_host'] .
                ';dbname='.$this->config['mysql_dbname'].';charset=utf8', $this->config['mysql_user'],
                $this->config['mysql_password']);
        }
        catch (Exception $exception)
        {
            exit('<strong>Unexpected exception:</strong> '. $exception->getMessage());
        }
    }

    protected function createGeocoder()
    {
        $geocoder = new \Geocoder\Geocoder();
        $adapter  = new \Geocoder\HttpAdapter\CurlHttpAdapter();
        $chain    = new \Geocoder\Provider\ChainProvider(array(
            new \Geocoder\Provider\FreeGeoIpProvider($adapter),
            new \Geocoder\Provider\HostIpProvider($adapter),
            new \Geocoder\Provider\GoogleMapsProvider($adapter, 'fr_FR', 'France', true)));
        $geocoder->registerProvider($chain);
        $this->geocoder = $geocoder;
    }
}

$app = new App();
$app->addHook(new \Becee\Hooks\UserHook($app));
$app->addHook(new \Becee\Hooks\ConfigHook($app));
$app->addHook(new \Becee\Hooks\FlashHook($app));
$app->run();
