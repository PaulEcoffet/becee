<?php

namespace Becee\Hooks;

class ConfigHook extends \QDE\Hook
{
    protected $app = null;

    public function __construct(\QDE\App &$app)
    {
        $this->app = $app;
    }

    public function getName()
    {
        return 'config_hook';
    }

    public function runDescending($response)
    {
        if($response instanceof \QDE\Responses\TwigResponse)
        {
            $oldnamespace = $response->getNamespace();
            $response->setNamespace('config');
            $config = $this->app->getConfig();
            $data = array('facebook_app_id' => $config['facebook_app_id']);
            $response->addData($data);
            $response->setNamespace($oldnamespace);
        }
        return $response;
    }
}
