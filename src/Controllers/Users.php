<?php

namespace Becee\Controllers;

use \Becee\Models\UsersManager;
use \Becee\Models\FilesManager;
use \Becee\Models\GeneralManager;

class Users
{
    public function registerAction($request, $city="Bordeaux")
    {
        $GeneralManager = new GeneralManager($request->getPdo());
        return $request->parseTemplate('add_user.html.twig', array('countries' => $GeneralManager->getCountries('nicename')));
    }
    public function registerProcessingAction($request)
    {
        $UsersManager = new UsersManager($request->getPdo());
        $FileManager = new FilesManager($request->getPdo());

        $user = $UsersManager->insertUser($request->getPost());

        $FILES = $request->getFiles();
        $filename = "becee_".time()."_".$user['id'];
        $path = $FileManager->uploadImage($FILES['user_avatar'], $filename,'avatars_users');
        if($path==NULL)
        {
            $path = '../media/img/default-user-avatar.png';
        }
        $UsersManager->insertUserAvatar($user['id'], $path);
    }
}
