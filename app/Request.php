<?php

namespace QDE;

use \PDO;

require_once 'config.php';


class Request
{
    private $db_connection;
    private $config;
    private $get_vars;
    private $uri_vars;
    protected $app;

    public function __construct(App &$app)
    {
        $this->config = get_config();
        $this->app = $app;
        try
        {
            $this->db_connection = new PDO('mysql:host='. $this->config['mysql_host'] .
                ';dbname='.$this->config['mysql_dbname'], $this->config['mysql_user'],
                $this->config['mysql_password'],
                array(PDO::ATTR_PERSISTENT => true));
        }
        catch (Exception $exception)
        {
            exit('<strong>Unexpected exception:</strong> '. $exception->getMessage());
        }
        $this->get_vars = $this->build_get();
    }

    protected function build_get()
    {
        $args_str_array = explode('?', $_SERVER['QUERY_STRING'], 2);
        $args_array = array();
        if (count($args_str_array) > 1)
        {
            parse_str(end($args_str_array), $args_array);
        }
        return $args_array;
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
        return $this->db_connection;
    }

    public function getQuery($key=null)
    {
        if(!empty($key))
        {
            return $this->get_vars[$key];
        }
        return $this->get_vars;
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
