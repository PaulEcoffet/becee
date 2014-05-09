<?php

namespace Becee\Controllers;

class Home
{
    public function indexAction($request)
    {
        try
        {
            $flash = $request->getCustomVariable('flash');
        }
        catch (\Exception $e)
        {
            $flash = array();
        }

        $BusinessManager = $request->getManager('Businesses');
        $user = $request->getManager('CurrentUser');
        $POST = $request->getPost();
        if(isset($POST['city']))
        {
            $user->setPrefferedCity($POST['city']);
        }
        if($user->hasPrefferedCity() === false)
        {
            $user->setPrefferedCityFromGeoLoc();
        }
        $prefCity = $user->getPrefferedCity();
        $cities = $BusinessManager->getCities(); //TODO: Why in BusinessManager?
        if($user->hasPrefferedCity() === false)
        {
            $user->setPrefferedCityFromGeoLoc();
        }

        $current_city = array(0, 'undefined');
        foreach($cities as $city)
        {
            if($city['id'] == $prefCity)
            {
                $current_city = $city;
            }
        }
        $categories = $BusinessManager->getBusinessCategories();
        if(isset($POST['search']))
        {
            $search = $POST['search'];
            $keywords = array_map('strtolower', explode(" ", $search));
            $categorie_name = $quality_name = $city_name = '';
            foreach ($categories as $categorie) {
                if (in_array($categorie['categorie_name'], $keywords)) {
                    $categorie_name = $categorie['categorie_name'];
                    $categorie_id = $categorie['categorie_id'];
                }
            }
            foreach ($cities as $city) {
                if (in_array(strtolower($city['name']), $keywords)) {
                    $city_name = $city['name'];
                    $prefCity = $city['id'];
                }
            }
            foreach (array('near', 'best', 'cheaper') as $quality) {
                if (in_array($quality, $keywords)) {
                    $quality_name = $quality;
                }
            }           
            $flash['information'] = array('id' => '#information', 
                'message' => 'Debug<hr/><strong>Request</strong> : '.$search.
                '<hr/><strong>Categorie</strong> : '.$categorie_name.
                '<hr/><strong>City</strong> : '.$city_name.
                '<hr/><strong>Quality</strong> : '.$quality_name); //DEBUG
            $businesses = $BusinessManager->getBusinesses($prefCity, $categorie_id);
        }
        else
        {
            $businesses = $BusinessManager->getBusinesses($prefCity);
        }
        $businesses = $BusinessManager->getBusinesses($prefCity, $categorie_id);
        $tags = $BusinessManager->getBusinessMostReleventTags(1, 5);
        return new \QDE\Responses\TwigResponse('home.html.twig',
            array('businesses' => $businesses, 'cities' => $cities, 
                'current_city' => $current_city, 'tags' => $tags,
                'categories' => $categories, 'flash' => $flash));
    }
}
