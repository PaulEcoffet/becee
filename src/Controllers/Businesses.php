<?php

namespace Becee\Controllers;

class Businesses
{

    public function viewBusinessAction($request) //Send information needed to generate a business page (using the id)
    {
        try
        {
            $flash = $request->getCustomVariable('flash');
        }
        catch (\Exception $e)
        {
            $flash = array();
        }

        $manager = $request->getManager('businesses');
        $id = $request->getParamsUri('id');
        $response = $manager->getBusinessById($id, array('with_images', 'with_comments'));
        $suggested_businesses = $manager->searchBusinesses(
            $response->city->name, 
            $response->categories[0]['categorie_name'],
            null,
            5);
        return new \QDE\Responses\TwigResponse(
            'view_business.html.twig', 
            array('business' => $response, 
                'suggested_businesses' => $suggested_businesses,
                'flash' => $flash));
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
        return new \QDE\Responses\TwigResponse('add_business.html.twig', 
            array('countries' => $countries, 'cities' => $cities));
    }

    public function business_clash($request)
    {
        $manager = $request->getManager('businesses');
        //TODO
    }

    public function addCommentAction($request)
    {
        $manager = $request->getManager('businesses');
        $business_id = $request->getParamsUri('id');
        $userManager = $request->getManager('currentUser');
        $user_id = $userManager->getId();
        $comment = $request->getPost('comment');
        $manager->insertComment($business_id, $user_id, $comment);
        if(!$error)
        {
            $informationArray = array('id' => '#information', 'message' => "Votre commentaire a été publié.");
        }
        else
        {
            $informationArray = array('id' => '#information', 'message' => 'Une erreur est survenu, votre commentaire n\'a pas été publié.');
        }
        return new \QDE\Responses\RedirectResponse(
            'view_business', 
            array('id' => $business_id), 
            array('information' => $informationArray));
    }
}
