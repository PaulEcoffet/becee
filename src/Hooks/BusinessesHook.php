<?php

namespace Becee\Hooks;

class BusinessesHook extends \QDE\Hook
{
    protected $app = null;

    public function __construct(\QDE\App &$app)
    {
        $this->app = $app;
    }

    public function getName()
    {
        return 'businesses_hook';
    }

    public function runDescending($response)
    {
        if($response instanceof \QDE\Responses\TwigResponse)
        {
            $oldnamespace = $response->getNamespace();
            $response->setNamespace('businesses_hook');
            $manager = $this->app->getManager('businesses');
            $data = array('categories' => $manager->getBusinessCategories());
            $response->addData($data);
            $response->setNamespace($oldnamespace);
        }
        return $response;
    }
}
