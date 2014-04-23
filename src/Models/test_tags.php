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

        $sql = 'SELECT *        -- ATM get everything, will later only get what matters
        FROM businesses, business_tags

        --INNER JOIN users
        --ON users.id = users.id_manager  --Getting Manager

        INNER JOIN businesses_addresses
        ON businesses.adress_id = addresses.id    --Getting adress

        --INNER JOIN cities
        --ON businesses.city_id = cities.id  --Getting city

        --INNER JOIN countries
        --ON businesses.country_id = country.id     --Getting country

        --INNER JOIN provinces
        --ON businesses.province_id = provinces.id -- Getting province

        --INNER JOIN business_images
        --ON businesses.id = business_images.business_id    --Getting Images

        --INNER JOIN link_business_tags                                   --TODO, Getting tags for this business
        --ON link_business_tags.id_business = businesses.id AND business_tags.id = link_business_tags.id_business_tag

        --INNER JOIN business_vist
        --ON business_visits.business_id = businesses.id ;-- Getting visit

        WHERE businesses.id = ?';

         //Need checking and testing, seems shitty


        $business_req = $bdd->prepare($sql);
        $business_req->execute(array(1));

        printf($business_req->fetch());


// to hydrate your object use FETCH_CLASS