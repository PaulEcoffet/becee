<?php

namespace Becee\Hooks;

class LocationHook extends \QDE\Hook
{
    protected $app = null;

    public function __construct(\QDE\App &$app)
    {
        $this->app = $app;
    }

    public function getName()
    {
        return 'location_hook';
    }

    public function runDescending($response)
    {
        if($response instanceof \QDE\Responses\TwigResponse)
        {
            $oldnamespace = $response->getNamespace();
            $response->setNamespace('location_hook');
            $manager = $this->app->getManager('location');
            $data = array('cities' => $manager->getCities());
            $response->addData($data);
            $response->setNamespace($oldnamespace);
        }
        return $response;
    }
}
