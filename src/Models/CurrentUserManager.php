<?php

namespace Becee\Models;

class CurrentUserManager
{
    private $app = null;

    public function __construct(\QDE\App $app)
    {
        $this->app = $app;
        if($this->app->hasSession('user_id') === false && $this->app->hasCookie('user_id') === true)
        {
            $this->connectUser($this->app->getCookie('user_id'));
        }
        elseif($this->app->hasSession('user_id') === false) //We create a fake account waiting for the user to sign in or sign up
        {
            $user = $app->getManager('Users')->createDummyUser();
            $this->app->setSession('user_id', $user->id);
            $this->app->setCookie('user_id', $user->id, time()+3600*24*31);
        }
    }

    public function hasPrefferedCity()
    {
        return $this->app->hasSession('user_preffered_city');
    }

    public function setPrefferedCityFromGeoLoc()
    {
        $this->setPrefferedCity(1); //TODO
    }

    public function setPrefferedCity($city_id)
    {
        $this->app->setSession('user_preffered_city', $city_id);
    }

    public function getPrefferedCity()
    {
        return $this->app->getSession('user_preffered_city');
    }
}
