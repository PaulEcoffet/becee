<?php

namespace QDE;

use \PDO;

require_once 'config.php';


class Request
{
    private $config;
    private $get_vars;
    private $uri_vars;
    protected $app;

    public function __construct(App &$app)
    {
        $this->config = get_config();
        $this->app = $app;
    }

    public function parseTemplate($file, $page_data)
    {
        $data['page'] = $page_data;
        /*foreach($this->template_hooks as $hook)
        {
            $data[$hook->getName()] = $hook->execute();
        }*/
        return $this->app->getTwig()->render($file, $data);
    }

    public function getPdo()
    {
        return $this->app->getPdo();
    }

    public function getApp()
    {
        return $this->app;
    }

    public function getQuery($key=null)
    {
        if(!empty($key))
        {
            return $this->$_GET[$key];
        }
        return $_GET;
    }

    public function setParamsUri($params)
    {
        $this->uri_vars = $params;
    }

    public function getParamsUri($key=null)
    {
        if(!empty($key))
        {
            return $this->uri_vars[$key];
        }
        return $uri_vars;
    }

    public function getPost($key=null)
    {
        if(!empty($key))
        {
            return $_POST[$key];
        }
        return $_POST;
    }

    public function getFiles($key=null)
    {
        if(!empty($key))
        {
            return $_FILES[$key];
        }
        return $_FILES;
    }

    public function getManager($name)
    {
        return $this->app->getManager($name);
    }
}
