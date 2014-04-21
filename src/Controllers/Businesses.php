<?php
namespace Becee\Controllers; 

class Businesses
{


	public function viewBusinessAction($request, $id) //Renvoi les infos nécessaire pour générer la page
	{
		// TODO
	}

	public function getBusiness() // TOUT DEDANS
	{
		// TODO
	}
	public function registerAction($request)
	{
		//INSERT INTO `businesses` VALUES (NULL, $name, $desc, NULL, 0, 0, NULL);
    	//$BusinessManager = new \Becee\Models\BusinessManager($request->getPdo());
    	//$businesses = $BusinessManager->getBusinessesByCity("bordeaux");
        return $request->parseTemplate('add_restaurant.html.twig', array());
	}
}



