<?php

namespace Becee\Hooks;

class UserHook extends \QDE\Hook
{
    protected $app = null;

    public function __construct(\QDE\App &$app)
    {
        $this->app = $app;
    }

    public function getName()
    {
        return 'user_hook';
    }

    public function runDescending($response)
    {
        if($response instanceof \QDE\Responses\TwigResponse)
        {
            $oldnamespace = $response->getNamespace();
            $response->setNamespace('user');
            $curuser = $this->app->getManager('CurrentUser');
            $data = array('is_logged' => $curuser->isLogged());
            if($curuser->isLogged())
            {
                $data['name'] = $curuser->getName();
                $data['id'] = $curuser->getId();
            }
            $response->addData($data);
            $response->setNamespace($oldnamespace);
        }
        return $response;
    }
}
