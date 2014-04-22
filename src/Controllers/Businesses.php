<?php

namespace Becee\Controllers;

use \Becee\Models\BusinessesManager;
use \Becee\Models\FilesManager;

class Businesses
{
    public function viewBusinessAction($request, $id) //Renvoi les infos nécessaire pour générer la page
    {
        // TODO
    }

    public function getBusiness() // TOUT DEDANS
    {
        // TODO
    }

    public function registerProcessingAction($request)
    {
        $BusinessManager = new BusinessesManager($request->getPdo());
        $FileManager = new FilesManager($request->getPdo());
        $FILES = $request->getFiles();
        $BusinessManager->insertBusiness($request->getPost(), $FileManager->uploadImage($FILES['img_business_med'], 'images_businesses'));
    }

    public function registerAction($request)
    {
        return $request->parseTemplate('add_restaurant.html.twig', array());
    }
}



