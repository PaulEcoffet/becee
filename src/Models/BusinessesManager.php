<?php

namespace Becee\Models;
use \Becee\Entities\Business;

class BusinessesManager
{
    private $pdo = NULL;

    public function __construct(\QDE\App $app)
    {
        $this->app = $app;
        $this->pdo = $this->app->getPdo();
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

    public function getBusinessById($business_id)

    {
        $new_business = new Business();

        $sql = 'SELECT businesses.name, businesses.id, business_addresses.line1,
        GROUP_CONCAT(business_categories.name) as category,
        businesses.website,
        businesses.email,
        businesses.phone_number,
        businesses.price,
        users.name,
        business_addresses.line1,
        business_addresses.line2,
        cities.name,
        provinces.name,
        countries.nicename


        /*
        GROUP_CONCAT(business_images.path),
        GROUP_CONCAT(business_tags.name) as tags,                            -- Will move to new request 
        GROUP_CONCAT(business_features.name) as features,
        GROUP_CONCAT(business_visits) as visits   */     

        FROM businesses

        INNER JOIN users 
        ON businesses.manager_id = users.id  /* Getting Manager */

        INNER JOIN business_addresses 
        ON business_addresses.business_id = businesses.id  /* Getting addresse */

        INNER JOIN cities 
        ON business_addresses.city_id = cities.id  /* Getting cities */

        INNER JOIN provinces
        ON cities.province_id = provinces.id    /* Getting province */

        INNER JOIN countries
        ON provinces.country_id = countries.id     /* Getting country */

        INNER JOIN business_images
        ON businesses.id = business_images.business_id    /* Getting Images (Path) */

        INNER JOIN link_business_tags 
        ON link_business_tags.business_id = businesses.id   /* NEED SEPARATED REQUEST !!, Getting alltags, separated by "," */
        INNER JOIN business_tags                            /* =========================== */
        ON business_tags.id = link_business_tags.tag_id

        INNER JOIN link_business_category
        ON link_business_category.business_id = businesses.id   /* Getting all categories, separated by "," */
        INNER JOIN business_categories
        ON business_categories.id = link_business_category.category_id

        INNER JOIN link_category_feature
        ON link_category_feature.category_id = business_categories.id
        INNER JOIN business_features                                /* Getting features */
        ON business_features.id = link_category_feature.feature_id

        INNER JOIN business_visits
        ON business_visits.business_id = businesses.id               /* Getting visit */

        WHERE businesses.id = ?
        ;'
        ;

        $business_req = $this->pdo->prepare($sql); 
        $business_req->execute(array($business_id));
        echo $business_id;
        printf($business_req->fetch(\PDO::FETCH_ASSOC));
        return $business_req->fetch(\PDO::FETCH_ASSOC);



    }

    public function getTags()
    {
        $sql = "SELECT * FROM business_tags;
        ;
        ";
        $business_req = $this->pdo->prepare($sql);
        $business_req->execute();
        return($business_req->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function getBusinessesByCity($city_id)
    {
        $sql = "SELECT b.name, b.description, ba.line1, bi.path
                FROM ((businesses b INNER JOIN business_addresses ba ON b.id = ba.business_id)
                    INNER JOIN cities c ON c.id = ba.city_id)
                    INNER JOIN business_images bi ON bi.business_id = b.id
                WHERE c.id =  :city_id
        ;
        ";
        $business_req = $this->pdo->prepare($sql);
        $business_req->bindValue(':city_id', $city_id,\PDO::PARAM_INT);
        $business_req->execute();
        return($business_req->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function getCities()
    {
        $business_req = $this->pdo->prepare('SELECT c.id, c.name FROM cities c;');
        $business_req->execute();
        return($business_req->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function insertBusinessImage($business_id, $image_path)
    {
        $sql = "INSERT INTO `business_images` (business_id, path)
                VALUES(:business_id, :image_path)
                ;
                ";

        $business_req = $this->pdo->prepare($sql);
        $business_req->bindValue(':image_path', $image_path,\PDO::PARAM_STR);
        $business_req->bindValue(':business_id', $business_id,\PDO::PARAM_INT);
        $business_req->execute();

    }
    public function insertBusiness($business, $image_path=NULL)
    {
        $sql = "INSERT INTO `businesses` (name, description)
                VALUES         (:name, :description)
                ;

                SELECT         MAX(id)
                FROM         `businesses`
                INTO         @LAST_ID
                ;


                INSERT INTO `provinces` (name, country_id)
                SELECT         :province,
                            (SELECT c.id FROM countries c WHERE c.name = :country LIMIT 1)
                FROM         dual
                WHERE         NOT EXISTS
                            (SELECT 1 from `provinces` WHERE name = :province and country_id = (SELECT c.id FROM provinces p INNER JOIN countries c ON p.country_id = c.id WHERE p.name = :province AND c.name = :country LIMIT 1))
                ;


                INSERT INTO `cities` (name, province_id)
                SELECT         :city,
                            (SELECT p.id FROM provinces p INNER JOIN countries c ON p.country_id = c.id WHERE p.name = :province AND c.name = :country LIMIT 1)
                FROM         dual
                WHERE         NOT EXISTS
                            (SELECT 1 from `cities` WHERE name = :city and province_id = (SELECT p.id FROM provinces p INNER JOIN countries c ON p.country_id = c.id WHERE p.name = :province AND c.name = :country LIMIT 1))
                ;


                INSERT INTO `business_addresses` (business_id, city_id, line1, line2, lat, lng)
                VALUES         (
                            @LAST_ID,
                            (SELECT cities.id FROM (provinces p INNER JOIN countries c ON p.country_id = c.id) INNER JOIN cities ON cities.province_id = p.id WHERE p.name = :province AND c.name = :country AND cities.name = :city LIMIT 1),
                            :line1,
                            :line2,
                            :lat,
                            :lng
                            )
                ;
                ";

        $business_req = $this->pdo->prepare($sql);
        $business_req->bindValue(':city', ucwords(strtolower($business['city'])),\PDO::PARAM_STR);
        $business_req->bindValue(':name', ucwords(strtolower($business['name'])),\PDO::PARAM_STR);
        $business_req->bindValue(':description', ucfirst(strtolower($business['description'])),\PDO::PARAM_STR);
        $business_req->bindValue(':province', ucwords(strtolower($business['province'])),\PDO::PARAM_STR);
        $business_req->bindValue(':country', ucwords(strtolower($business['country'])),\PDO::PARAM_STR);
        $business_req->bindValue(':line1', ucwords(strtolower($business['line1'])),\PDO::PARAM_STR);
        $business_req->bindValue(':line2', ucwords(strtolower($business['line2'])),\PDO::PARAM_STR);
        $business_req->bindValue(':lat', ucwords(strtolower($business['lat'])),\PDO::PARAM_STR);
        $business_req->bindValue(':lng', ucwords(strtolower($business['lng'])),\PDO::PARAM_STR);
        $business_req->bindValue(':image_path', $image_path,\PDO::PARAM_STR);
        $business_req->execute();

        $sql = "SELECT * FROM `businesses` WHERE id = (SELECT MAX(id) FROM `businesses`);";

        $business_req = $this->pdo->prepare($sql);
        $business_req->execute();

        return $business_req->fetch(\PDO::FETCH_ASSOC);
    }
}
