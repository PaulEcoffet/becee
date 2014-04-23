<?php

namespace Becee\Controllers;

use \Becee\Models\BusinessesManager;
use \Becee\Models\FilesManager;
use \Becee\Models\GeneralManager;

class Users
{
    public function registerAction($request, $city="Bordeaux")
    {
        $GeneralManager = new GeneralManager($request->getPdo());
        return $request->parseTemplate('add_user.html.twig', array('countries' => $GeneralManager->getCountries('nicename')));
    }
}
