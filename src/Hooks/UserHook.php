<?php

namespace Becee\Hooks;

class UserHook implements \QDE\Hook
{
    public function __construct(\QDE\App &$app)
    {
        $this->app = $app;
    }

    public function getName()
    {
        return 'user_hook';
    }

    public function run($response)
    {
        if($response instanceof \QDE\Responses\TwigResponse)
        {
            $response->setNamespace('user');
            $curuser = $this->app->getManager('CurrentUser');
            $data = array('is_logged' => $curuser->isLogged());
            if($curuser->isLogged())
            {
                $data['name'] = $curuser->getName();
                $data['id'] = $curuser->getId();
            }
            $response->addData($data);
        }
        return $response;
    }
}
