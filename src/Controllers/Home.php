<?php

namespace Becee\Controllers;

include '..\src\Models\BusinessesManager.php';

class Home
{
    public function indexAction($request, $city="bordeaux")
    {
    	$BusinessManager = new \Becee\Models\BusinessManager($request->getPdo());
    	$POST = $request->getPost();
    	if(isset($POST['city'])) {
    		$city = $POST['city'];
		}
    	$businesses = $BusinessManager->getBusinessesByCity($city);
    	$cities = $BusinessManager->getCities();
        return $request->parseTemplate('home.html.twig', array('businesses' => $businesses, 'cities' => $cities, 'current_city' => $city));
    }
}
