<?php

namespace Becee\Models;

use \Becee\Entities\City;
use \Becee\Entities\Country;


class LocationManager
{
    protected $app;

    public function __construct(\QDE\App $app)
    {
        $this->app = $app;
        $this->pdo = $this->app->getPdo();
    }

    public function getCities($city_id = '%')
    {
        $sql = 'SELECT c.id as city_id, c.name as city_name, c.lat as city_lat, c.lng as city_lng,
                p.id as province_id, p.name as province_name, countries.id as country_id,
                countries.name as country_name, pc.code as postal_code
            FROM cities c
            INNER JOIN postal_codes pc ON pc.id = c.id
            INNER JOIN provinces p ON c.province_id = p.id
            INNER JOIN countries ON p.country_id = countries.id
            WHERE c.id LIKE ?
            ;';
        $cities_req = $this->pdo->prepare($sql);
        $cities_req->execute(array($city_id));
        $results = $cities_req->fetchAll(\PDO::FETCH_ASSOC);
        if(!($city_id == '%'))
        {
            return new City($results[0]);
        }
        $cities = array();
        foreach($results as $result)
        {
            $cities[] = new City($result);
        }
        return $cities;
    }

   /* public function getZones()
    {
        $sql = '-- TODO';
        $zones_req = $this->pdo->prepare($sql);
        $zones_req->execute();
        $results = $zones_req->fetchAll(\PDO::FETCH_ASSOC);
        $zones = array();
        foreach($results as $result)
        {
            $zones[] = new Zone($result);
        }
        return $zones;
    }

    public function getProvinces()
    {
        $sql = '-- TODO';
        $provinces_req = $this->pdo->prepare($sql);
        $provinces_req->execute();
        $results = $provinces_req->fetchAll(\PDO::FETCH_ASSOC);
        $provinces = array();
        foreach($results as $result)
        {
            $provinces[] = new Province($result);
        }
        return $provinces;
    } */

    public function getCountries()
    {
        $sql = "
        SELECT * 
        FROM `countries`
        ;
        ";
        $business_req = $this->pdo->prepare($sql);
        $business_req->execute();
        $results = $business_req->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
        //$countries = array();
        //foreach($results as $result)
        //{
        //    $countries[] = new Country($result);
        //}
        //return $countries;
    }

    public function getNearestZone($lat, $lng)
    {
        $sql = 'SELECT c.id as city_id, c.name as city_name, c.lat as city_lat, c.lng as city_lng,
                p.id as province_id, p.name as province_name, countries.id as country_id,
                countries.name as country_name
            FROM cities c
            INNER JOIN provinces p ON c.province_id = p.id
            INNER JOIN countries ON p.country_id = countries.id
            INNER JOIN zones ON c.id = zones.id
            ORDER BY
            ((ACOS(SIN(:lat * PI() / 180) * SIN(c.lat * PI() / 180) + COS(:lat * PI() / 180) * COS(c.lat * PI() / 180) * COS((:lng - c.lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) -- from http://zcentric.com/2010/03/11/calculate-distance-in-mysql-with-latitude-and-longitude/
            ASC
            LIMIT 1;';
        $city_req = $this->pdo->prepare($sql);
        $city_req->bindValue('lat', $lat);
        $city_req->bindValue('lng', $lng);
        $city_req->execute();

        return new City($city_req->fetch(\PDO::FETCH_ASSOC));
    }
}
