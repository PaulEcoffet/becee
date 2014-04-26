<?php

        class Business
{

    public $name = NULL;
    public $id = NULL; 
    public $longitude = NULL;
    public $latitude = NULL;
    public $manager = NULL;
    public $website = NULL;
    public $tags = NULL;
    public $images = NULL;
    public $visits = NULL;
    public $comments = NULL;
    public $features = NULL;

    public function add_features($new_features)
    {
        $this->$features = array_merge($this->$features, $new_features);
    }

    public function add_tags($new_tags)

    {
        $this->$tags = array_merge($this->$tags, $new_tags);
    }

    
}


        $bdd = new PDO('mysql:host=localhost;dbname=becee', 'root', '');

        $new_business = new Business();

        $sql = 'SELECT *   --GROUP_CONCAT(business_tags)     -- ATM get everything, will later only get what matters
        FROM businesses, business_tags

         INNER JOIN users 
         ON businesses.manager_id = users.id  --Getting Manager

        INNER JOIN business_addresses 
        ON business_addresses.business_id = businesses.id  --Getting adresse

        INNER JOIN cities 
        ON business_addresses.city_id = cities.id  --Getting cities

        INNER JOIN provinces
        ON cities.province_id = provinces.id -- Getting province

        INNER JOIN countries
        ON province.country_id = countries.id     --Getting country

        INNER JOIN business_images
        ON businesses.id = business_images.business_id    --Getting Images (Path)

       INNER JOIN link_business_tags 
       ON link_business_id = businesses.id                   --Getting alltags, separated by ","
       INNER JOIN business_tags 
       ON business_tags.id = link_business_tags.tag_id


        --INNER JOIN business_vist
        --ON business_visits.business_id = businesses.id ;-- Getting visit

        WHERE businesses.id = ?';

         //Need checking and testing, seems shitty


        $business_req = $bdd->prepare($sql);
        $business_req->execute(array(1));

        printf($business_req->fetch());


// to hydrate your object use FETCH_CLASS