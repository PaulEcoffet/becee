<?php

namespace QDE\Responses;

class TwigResponse implements Response
{
    private $layout = '';
    private $data = array();
    private $namespace = 'page';

    public function __construct($layout=null, $data=null, $namespace='page')
    {
        if ($layout !== null)
        {
            $this->layout = $layout;
        }

        $this->setNamespace($namespace);
        if ($data !== null)
        {
            $this->data[$this->namespace] = $data;
        }
    }

    public function run(\QDE\App $app)
    {
        $twig = $app->getTwig();
        return $twig->render($this->layout, $this->data);
    }

    public function setLayout($layout)
    {
        $this->layout = layout;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function addData($name, $value=null)
    {
        if (is_array($name))
        {
            $this->data[$this->namespace] = array_merge($name, $this->data[$this->namespace]);
        }
        else
        {
            $this->data[$this->namespace][$name] = $value;
        }
    }

    public function deleteData($name, $namespace=null)
    {
        $cur_namespace = ($namespace !== null)? $namespace : $this->namespace;
        unset($this->data[$cur_namespace][$name]);
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        if(!isset($this->data[$this->namespace]))
        {
            $this->data[$this->namespace] = array();
        }
    }

    public function getNamespace()
    {
        return $this->namespace;
    }
}
