<?php

namespace Becee\Models;

class BusinessesManager
{
    private $pdo = NULL;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getBusinessByIdWithoutManager($business_id) //Get the busines name, longitude, latitude, website by using his id
    {
        $business_req = $this->pdo->prepare('SELECT id, name, longitude, latitude, website FROM businesses WHERE id = ?');
        $business_req->execute($business_id);
        return($business_req->fetch());
    }

    public function getBusinessByIdWithManager($business_id) //Get the busines name, longitude, latitude, website AND his manager by using his id
    {
        $business_req = $this->pdo->prepare('SELECT * FROM businesses INNER JOIN Users ON id_manager = user.id WHERE businesses.id = ?');
        $business_req->execute($business_id);
        return($business_req->fetch());
    }

    public function getBusinessById($business_id)// NOT OVER
    {
        $new_business = new Business();

        $sql = 'SELECT *        -- ATM get everything, will later only get what matters
        FROM businesses, business_tags

        INNER JOIN users
        ON users.id = users.id_manager  --Getting Manager

        INNER JOIN adresses
        ON businesses.adress_id = addresses.id    --Getting adress

        INNER JOIN cities
        ON businesses.city_id = cities.id  --Getting city

        INNER JOIN countries
        ON businesses.country_id = country.id     --Getting country

        INNER JOIN provinces
        ON businesses.province_id = provinces.id -- Getting province

        INNER JOIN business_images
        ON businesses.id = business_images.business_id    --Getting Images

        INNER JOIN link_business_tags                                   --TODO, Getting tags for this business
        ON link_business_tags.id_business = businesses.id AND business_tags.id = link_business_tags.id_business_tag

        INNER JOIN business_vist
        ON business_visits.business_id = businesses.id ;-- Getting visit'

         //Need checking and testing, seems shitty


        $business_req = $this->pdo->prepare($sql);


        
    }


    public function getBusinessesByCity($city)
    {
        $sql = 'SELECT b.name, b.description FROM (businesses b INNER JOIN cities c ON b.city_id = c.id) WHERE c.name = "'.$city.'";';
        $business_req = $this->pdo->prepare($sql);
        $business_req->execute();
        return($business_req->fetchAll(\PDO::FETCH_ASSOC));
    }
    public function getCities()
    {
        $business_req = $this->pdo->prepare('SELECT c.name FROM cities c;');
        $business_req->execute();
        return($business_req->fetchAll(\PDO::FETCH_ASSOC));
    }
    public function insertBusiness($business)
    {
        $sql = "
        INSERT INTO `cities` (name)
        VALUES('".$business['city']."');
        INSERT INTO `businesses` (name, description, city_id) VALUES(
               '".$business['name']."',
               '".$business['description']."',
               (SELECT id FROM cities WHERE cities.name = '".$business['city']."')
               );";
        $business_req = $this->pdo->prepare($sql);
        $business_req->execute();
    }
}