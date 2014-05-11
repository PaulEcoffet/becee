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
        $cities = $BusinessManager->getCities(); //TODO: Why in BusinessManager?
        if($user->hasPrefferedCity() === false)
        {
            $user->setPrefferedCityFromGeoLoc();
        }
        $prefCity = $user->getPrefferedCity();
        $categories = $BusinessManager->getBusinessCategories();
        if(isset($POST['search']))
        {
            $search = $POST['search'];
            $category_names = array();
            foreach ($categories as $value)
            {
                $category_names[] = $value['categorie_name'];
            }
            list($category, $keywords, $location) = $this->parseSearch($search, $category_names);
            $flash['information'] = array('id' => '#information',
                'message' => 'Debug<hr/><strong>Request</strong> : '.$search.
                '<hr/><strong>Category</strong> : '. $category .
                '<hr/><strong>Keywords</strong> : '.implode(',', $keywords).
                '<hr/><strong>City</strong> : '.$location);
            if($location === null or $location == 'moi')
            {
                $location = $prefCity->name;
            }
            if($category === null)
            {
                $category = '%';
            }
            $businesses = $BusinessManager->searchBusinesses($location, $category, $keywords);

        }
        else
        {
            $businesses = $BusinessManager->searchBusinesses($prefCity->name);
        }
        return new \QDE\Responses\TwigResponse('home.html.twig',
            array('businesses' => $businesses, 'cities' => $cities,
                'current_city' => $prefCity,
                'categories' => $categories, 'flash' => $flash));
    }

    private function parseSearch($str, $categories)
    {
        $black_list = array('le', 'la', 'les', 'un', 'une', 'des', 'de', 'avec');
        $location_delimiters = array('pres de', 'a cote', 'dans les environs', 'a', 'near');
        $location = null;

        $str = strtolower(iconv('ISO-8859-1','ASCII//TRANSLIT',$str)); //Removing accents and weird chars
        $keywords_str = $str;
        foreach($location_delimiters as $loc_del)
        {
            $result = array_map('strrev', explode(strrev(' '.$loc_del.' '), strrev($str))); //explode by the right
            if(isset($result[1]))
            {
                $keywords_str = $result[1];
                $location = $result[0];
                break;
            }
        }
        $keywords = explode(' ', $keywords_str);
        $keywords = array_diff($keywords, $black_list);
        $categories_found = array_intersect($keywords, $categories);
        if(count($categories_found) > 0)
        {
            $category = $categories_found[0];
        }
        else
        {
            $category = null;
        }
        $keywords = array_values(array_diff($keywords, $categories));
        return array($category, $keywords, $location);
    }
}
