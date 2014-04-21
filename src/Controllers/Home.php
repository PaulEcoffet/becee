<?php

namespace Becee\Controllers;

include '..\src\Models\BusinessesManager.php';

class Home
{
    public function indexAction($request)
    {
    	$BusinessManager = new \Becee\Models\BusinessManager($request->getPdo());
    	$businesses = $BusinessManager->getBusinessesByCity("bordeaux");
        return $request->parseTemplate('home.html.twig', array('businesses' => $businesses));
    }
}
