<?php

namespace QDE\Responses;

class RedirectResponse implements Response
{
    protected $route = null;
    protected $variables = array();

    public function __construct($route, $variables=null)
    {
        $this->route = $route;
        $this->variables = $variables;
    }

    public function run(\QDE\App $app)
    {
        $app->setHeader('Location: '. $app->getPath($this->route, $this->variables));
        return '';
    }
}
