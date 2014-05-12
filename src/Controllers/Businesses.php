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
            $path = 'img/default-business-img.png';
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
        $business_id1 = $request->getParamsUri('business_id1');
        $business_id2 = $request->getParamsUri('business_id2');
        $winner_id = $request->getParamsUri('winner_id');
        $feature_id = $request->getParamsUri('feature_id');

        $manager->businessesComparaisonByFeature($business_id1, $business_id2, $winner_id, $feature_id);
        return new \QDE\Responses\TwigResponse('comparaisons_processing.html.twig');
        //TODO
    }

    public function voteCommentAction($request)
    {
        $manager = $request->getManager('businesses');
        $business_id = $request->getParamsUri('business_id');
        $comment_id = $request->getParamsUri('comment_id');
        $manager->insertVoteToComment($comment_id, $request->getPost('vote') === 'pos');

        return new \QDE\Responses\RedirectResponse(
            'view_business', 
            array('id' => $business_id), 
            array('information' => $informationArray));
    }

    public function addCommentAction($request)
    {
        $manager = $request->getManager('businesses');
        $business_id = $request->getParamsUri('id');
        $userManager = $request->getManager('currentUser');
        $user_id = $userManager->getId();
        $comment = $request->getPost('comment');
        $image_info = '';
        $path = '';
        $image_id = null;
        if (!empty($comment)) {
            $comment_id = $manager->insertComment($business_id, $user_id, $comment);
            $image = $request->getFiles('business_user_image');
            if ($image['error'] === 0 && $image['size'] !== 0) {
                $image_info = print_r($image, true);
                $FilesManager = $request->getManager('files');
                $filename = "becee_".time()."_".$business['id'];
                $path = $FilesManager->uploadImage($image, $filename,'images_businesses');
                if (!empty($path)) {
                    $image_id = $manager->insertBusinessImage($business_id, $path);
                    $manager->linkCommentWithImage($comment_id, $image_id);
                }
                else
                {
                    $error = 'Image upload failed.';
                }
            }
            $informationArray = array('id' => '#information', 'message' => "<hr/>Le commentaire suivant a été publié :<hr/>".$comment.'<hr/>L\'image suivante a été attaché au commentaire :<hr/>'.$image_info.'<hr/>path:'.$path.' image_id:'.$image_id.'<hr/>'.' business_id:'.$business_id.' comment_id:'.$comment_id);
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
