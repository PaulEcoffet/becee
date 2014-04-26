<?php

namespace Becee\Controllers;

use \Becee\Models\BusinessesManager;

class Home
{
    public function indexAction($request)
    {
        $BusinessManager = new BusinessesManager($request->getPdo());
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
        $businesses = $BusinessManager->getBusinessesByCity($prefCity);
        $cities = $BusinessManager->getCities(); //TODO: Why in BusinessManager?
        $current_city = array(0, 'undefined');
        foreach($cities as $city)
        {
            if($city['id'] == $prefCity)
            {
                $current_city = $city;
            }
        }
        $tags = $BusinessManager->getTags();
        return $request->parseTemplate('home.html.twig', array('businesses' => $businesses, 'cities' => $cities, 'current_city' => $current_city, 'tags' => $tags));
    }
}
