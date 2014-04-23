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
        ON business_visits.business_id = businesses.id ;-- Getting visit';

         //Need checking and testing, seems shitty


        $business_req = $this->pdo->prepare($sql);


        
    }


    public function getBusinessesByCity($city)
    {
        $sql = "
        SELECT b.name, b.description, bi.path 
        FROM ((businesses b INNER JOIN business_addresses ba ON b.id = ba.business_id) INNER JOIN cities c ON c.id = ba.city_id) INNER JOIN business_images bi ON bi.business_id = b.id WHERE c.name =  '".$city."'
        ;
        ";
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
    public function insertBusiness($business, $image_path=NULL)
    {
        $sql = 'INSERT INTO `businesses` (name, description) VALUES( :name, :description)
                ;

                SELECT LAST_INSERT_ID() INTO @LAST_ID;

                INSERT INTO test VALUES(@LAST_ID);
                INSERT INTO `business_images` (business_id, path)
                VALUES(
                @LAST_ID,
                "lol"
                )
                ;

                INSERT INTO `provinces` (name, country_id)
                SELECT :province, (SELECT id FROM countries WHERE countries.nicename = :country)
                FROM dual
                WHERE NOT EXISTS
                (SELECT 1 from `provinces` WHERE name = :province and country_id = (SELECT id FROM `countries` WHERE countries.nicename = :country))
                ;

                INSERT INTO `cities` (name, province_id)
                SELECT :city,
                (SELECT id FROM provinces WHERE provinces.name = :province)
                FROM dual
                WHERE NOT EXISTS
                (SELECT 1 from `cities` WHERE name = :city and province_id = (SELECT id FROM `provinces` WHERE provinces.name = :province))
                ;

                INSERT INTO test VALUES(@LAST_ID);

                INSERT INTO `business_addresses` (business_id, city_id, line1, line2, lat, lng)
                VALUES(
                @LAST_ID,
                (SELECT id FROM cities WHERE cities.name = :city),
                :line1, :line2, :lat, :lng )
                ;

                ';
        
        $business_req = $this->pdo->prepare($sql); 
        $business_req->bindValue('name', ucwords(strtolower($business['name'])), \PDO::PARAM_STR);
        $business_req->bindValue('description', ucfirst(strtolower($business['description'])), \PDO::PARAM_STR);
        $business_req->bindValue('province', ucwords(strtolower($business['province'])), \PDO::PARAM_STR);
        $business_req->bindValue('country', ucwords(strtolower($business['country'])), \PDO::PARAM_STR);
        $business_req->bindValue('city', ucwords(strtolower($business['city'])), \PDO::PARAM_STR);
        $business_req->bindValue('line1', ucwords(strtolower($business['line1'])));
        $business_req->bindValue('line2', ucwords(strtolower($business['line2'])));
        $business_req->bindValue('lat', ucwords(strtolower($business['lat'])));
        $business_req->bindValue('lng', ucwords(strtolower($business['lng'])));

        echo $sql;

       

        $business_req->execute(); 

        echo '<br /><br /><br />';

        echo $business_req->errorCode();

        print_r($business_req->errorInfo());
    }
}
