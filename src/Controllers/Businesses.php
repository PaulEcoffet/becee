<?php

namespace Becee\Controllers;

use \Becee\Models\BusinessesManager;
use \Becee\Models\FilesManager;
use \Becee\Models\GeneralManager;

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

        $business = $BusinessManager->insertBusiness($request->getPost());

        $FILES = $request->getFiles();
        $filename = "becee_".time()."_".$business['id'];
        $path = $FileManager->uploadImage($FILES['img_business_med'], $filename,'images_businesses');
        if($path==NULL)
        {
            $path = '../media/img/default-business-img.png';
        }
        $BusinessManager->insertBusinessImage($business['id'], $path);
    }

    public function registerAction($request)
    {
        $GeneralManager = new GeneralManager($request->getPdo());
        return new \QDE\Responses\TwigResponse('add_business.html.twig', array('countries' => $GeneralManager->getCountries('nicename')));
    }
}



