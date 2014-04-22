<?php
require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once 'config.php';
require_once 'Hook.php';

$loader = new Twig_Loader_Filesystem('../src/tpl');
$twig = new Twig_Environment($loader, array(
    'cache' => '../cache/tpl',
    'debug' => get_config()['debug']));

class Request
{
    private $db_connection;
    private $config;
    private $get_vars;
    private $template_hooks = array();

    public function __construct()
    {
        $this->config = get_config();
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
        global $twig;
        $data['page'] = $page_data;
        foreach($this->template_hooks as $hook)
        {
            $data[$hook->getName] = $hook->execute();
        }
        return $twig->render($file, $data);
    }

    public function addTemplateHook(Hook $hook)
    {
        $this->template_hooks[] = $hook;
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
        // TODO
    }
}
