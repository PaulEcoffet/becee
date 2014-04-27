<?php

namespace Becee\Controllers;

use \Becee\Models\BusinessesManager;
use \Becee\Models\FilesManager;

class Businesses
{
    public function viewBusinessAction($request, $id) //Send information needed to generate a business page (using the id)
    {

        //Need to create business_page.html.twig
        $reponse = $getbusiness($request, $id);
        return new \QDE\Responses\TwigResponse('view_business.html.twig', array('countries' => $LocationManager->getCountries('nicename')));;
    }

    public function getBusiness($request, $id) //Call manager which get the whole business by id
    {
        $manager = $request->getManager('businesses');
        $manager->getBusinessById($id);
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
        $LocationManager = $request->getManager('Location');
        $countries = $LocationManager->getCountries();
        $cities = $LocationManager->getCities();
        return new \QDE\Responses\TwigResponse('add_business.html.twig', array('countries' => $countries, 'cities' => $cities));
    }
}
