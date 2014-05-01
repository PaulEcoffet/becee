<?php

namespace Becee\Models;

class CurrentUserManager
{
    private $app = null;

    public function __construct(\QDE\App $app)
    {
        $this->app = $app;
        $this->pdo = $this->app->getPdo();
        if($this->app->hasSession('user_id') === false && $this->app->hasCookie('user_id') === true)
        {
            echo $this->app->getCookie('user_id');
            $this->connectUser(array('id' => $this->app->getCookie('user_id'), 'name' => 'visitor'));

        }
        elseif($this->app->hasSession('user_id') === false) //We create a fake account waiting for the user to sign in or sign up
        {
            $user = $app->getManager('Users')->createDummyUser();
            $this->app->setSession('user_id', $user->id);
            $this->app->setSession('user_session_type', 'dummy');
            $this->app->setCookie('user_id', $user->id, time()+3600*24*31);
        }
    }

    public function hasPrefferedCity()
    {
        return $this->app->hasSession('user_preffered_city');
    }

    public function connectUser($user)
    {
        $this->app->setSession('user_id', $user['id']);
        $this->app->setSession('user_name', $user['name']);
        $this->app->setSession('user_session_type', 'normal');
        $this->app->setCookie('user_id', $user['id'], time()+3600*24*31);
        
        $business_req = $this->pdo->prepare("UPDATE `users` SET last_visit_time=NOW() WHERE id = ?;"); 
        $business_req->execute(array($user['id']));
    }

    public function disconnectUser()
    {
        $this->app->deleteSession('user_id');
        $this->app->deleteSession('user_name');
        $this->app->deleteSession('user_session_type');
        $this->app->deleteCookie('user_id');
    }

    public function isLogged()
    {
        return ($this->app->hasSession('user_session_type') === true
            && $this->app->getSession('user_session_type') !== 'dummy');
    }

    public function getName()
    {
        return $this->app->getSession('user_name');
    }

    public function getId()
    {
        return $this->app->getSession('user_id');
    }

    public function setPrefferedCityFromGeoLoc() //Warning: Do not work in local
    {
        if($this->app->getConfig()['debug'])
        {
            //$ip = '88.190.16.36'; // Paris ip
            $ip = '90.55.18.3'; //Le Barp ip
        }
        else
        {
            $ip = $this->app->getClientIp();
        }
        $geocode = $this->app->getGeocoder()->geocode($ip);
        $nearestcity = $this->app->getManager('Location')->getNearestZone($geocode->getLatitude(), $geocode->getLongitude());
        $this->setPrefferedCity($nearestcity->id);
    }

    public function setPrefferedCity($city_id)
    {
        $this->app->setSession('user_preffered_city', $city_id);
    }

    public function getPrefferedCity()
    {
        return $this->app->getSession('user_preffered_city');
    }

    public function setAvatarImage()
    {
        $FileManager = $this->app->getManager('files');
        $tmp_files = $this->app->getFiles();
        $filename = 'becee_'.time().'_'.$user->id;
        $foldername = 'avatars_users';
        $path = $FileManager->uploadImage($tmp_files['user_avatar'], $filename, $foldername);
        if($path==NULL)
        {
            $path = '../media/img/default-user-avatar.png';
        }
        $sql = "UPDATE `users` SET avatar_path=:avatar_path WHERE id = :user_id;";
        
        $business_req = $this->pdo->prepare($sql); 
        $business_req->bindValue(':avatar_path', $path,\PDO::PARAM_STR);
        $business_req->bindValue(':user_id', $user->id,\PDO::PARAM_INT);
        $business_req->execute();

    }
}
