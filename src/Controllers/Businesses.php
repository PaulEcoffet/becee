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

    public function registerProcessingAction($request)
    {
        $BusinessManager = new \Becee\Models\BusinessManager($request->getPdo());
        $businesses = $BusinessManager->insertBusiness($request->getPost());
    }

    public function registerAction($request)
    {
        return $request->parseTemplate('add_restaurant.html.twig', array());
    }
}



