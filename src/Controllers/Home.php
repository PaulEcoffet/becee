<?php

namespace Becee\Controllers;

use \Becee\Models\BusinessesManager;

class Home
{
    public function indexAction($request, $city="bordeaux")
    {
        $BusinessManager = new BusinessesManager($request->getPdo());
        $POST = $request->getPost();
        if(isset($POST['city'])) {
            $city = $POST['city'];
        }
        $businesses = $BusinessManager->getBusinessesByCity($city);
        $cities = $BusinessManager->getCities();
        return $request->parseTemplate('home.html.twig', array('businesses' => $businesses, 'cities' => $cities, 'current_city' => $city));
    }
}
