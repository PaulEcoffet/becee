<?php

namespace Becee\Controllers;

class Businesses
{
    public function viewBusinessAction($request) //Send information needed to generate a business page (using the id)
    {

        $manager = $request->getManager('businesses');
        $id = $request->getParamsUri('id');
        $response = $manager->getBusinessById($id, array('with_images', 'with_comments'));
        return new \QDE\Responses\TwigResponse('view_business.html.twig', array('business' => $response));
    }

    public function registerProcessingAction($request)
    {
        $BusinessManager = $request->getManager('businesses');
        $FilesManager = $request->getManager('files');

        $business = $BusinessManager->insertBusiness($request->getPost());

        $FILES = $request->getFiles();
        $filename = "becee_".time()."_".$business['id'];
        $path = $FilesManager->uploadImage($FILES['img_business_med'], $filename,'images_businesses');
        if($path==NULL)
        {
            $path = '../media/img/default-business-img.png';
        }
        $BusinessManager->insertBusinessImage($business['id'], $path);
        echo "<br/><a href='../../'>Back to Home</a>";
    }

    public function registerAction($request)
    {
        $LocationManager = $request->getManager('Location');
        $countries = $LocationManager->getCountries();
        $cities = $LocationManager->getCities();
        return new \QDE\Responses\TwigResponse('add_business.html.twig', array('countries' => $countries, 'cities' => $cities));
    }

    public function business_clash($request)
    {
        $manager = $request->getManager('businesses');
        //TODO
        
    }
}
