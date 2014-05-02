<?php

namespace QDE\Responses;

class RedirectResponse implements Response
{
    protected $route_name = null;
    protected $route_variables = array();
    protected $flash_variables = array();

    public function __construct($route_name, $route_variables=null, $flash_variables=null)
    {
        $this->route_name = $route_name;
        $this->route_variables = $route_variables;
        $this->flash_variables = $flash_variables;
    }

    public function run(\QDE\App $app)
    {
        $app->getHook('flash_hook')->setVariablesForPage($this->route_name, $this->flash_variables);
        $app->setHeader('Location: '. $app->getPath($this->route_name, $this->route_variables));
        return '';
    }
}
