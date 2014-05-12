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
        $visited_businesses_id = $manager->getVisitedBusinesses();
        $visited_businesses = array();
        foreach ($visited_businesses_id as $businessid => $value) {
            array_push($visited_businesses, $manager->getBusinessById($businessid));
        }
        return new \QDE\Responses\TwigResponse(
            'view_business.html.twig', 
            array('business' => $response, 
                'suggested_businesses' => $suggested_businesses,
                'visited_businesses' => $visited_businesses,
                'flash' => $flash));
    }

    public function addVisitAction($request)
    {
        $businessManager = $request->getManager('businesses');
        $id = intval($request->getParamsUri('id'));
        if($id != 0)
        {
            $businessManager->insertVisit($id);
            return new \QDE\Responses\RedirectResponse('view_business', array('id' => $id),
                array('information' => array('id'=>'#information', 'message'=>'Votre visite a bien été prise en compte')));
        }
        else
        {
            return new \QDE\Responses\RedirectResponse('view_business', array('id' => $id),
                array('information' => array('id' => '#information', 'message' => 'Votre visite n\'a pas été prise en compte')));
        }
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
        $manager->insertVoteToComment($business_id, $comment_id, $request->getPost('vote') === 'pos');

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
                    $manager->linkCommentWithImage($business_id, $comment_id, $image_id);
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


    public function computeScoreAction($request)
    {
        $start = microtime(true);
        $businessScore = $request->getManager('BusinessScore');
        $businessScore->compute_score();
        echo microtime(true) - $start;
    }

    public function clashProcessAction($request)
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
        $id_business1 = $request->getParamsUri('id_business1');
        $id_business2 = $request->getParamsUri('id_business2');
        $features_business1 = $request->getPost('features_business1');
        $features_business2 = $request->getPost('features_business2');
        foreach ($features_business1 as $feature_id) {
            $manager->businessesComparaisonByFeature($id_business1, $id_business2, $id_business1, $feature_id);
        }
        foreach ($features_business2 as $feature_id) {
            $manager->businessesComparaisonByFeature($id_business1, $id_business2, $id_business2, $feature_id);
        }
        return new \QDE\Responses\RedirectResponse(
            'home', 
            array('id' => $business_id), 
            array('information' => array('id'=>'#information', 
                'message' => 'Votre clash a bien été pris en compte<hr/> - id_business1 = '.$id_business1.' <br/> id_business2 = '.$id_business2)));
    }

    public function clashAction($request)
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
        $business_id1 = $request->getParamsUri('business_id1');
        $business_id2 = $request->getParamsUri('business_id2');
        $response1 = $manager->getBusinessById($business_id1, array('with_images', 'with_comments'));
        $response2 = $manager->getBusinessById($business_id2, array('with_images', 'with_comments'));
        $all_features = $manager->getAllFeatures();
        return new \QDE\Responses\TwigResponse(
            'clash_businesses.html.twig', 
            array(
                'features' => $all_features,
                'business1' => $response1,
                'business2' => $response2, 
                'flash' => $flash));
    }
}
