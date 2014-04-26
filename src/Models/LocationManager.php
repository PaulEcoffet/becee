<?php

namespace Becee\Models;

use \Becee\Entities\City;

class LocationManager
{
    protected $app;

    public function __construct(\QDE\App $app)
    {
        $this->app = $app;
        $this->pdo = $this->app->getPdo();
    }

    public function getCities()
    {
        $sql = 'SELECT c.id as city_id, c.name as city_name, c.lat as city_lat, c.lng as city_lng,
                p.id as province_id, p.name as province_name, countries.id as country_id,
                countries.name as country_name
            FROM cities c
            INNER JOIN provinces p ON c.province_id = p.id
            INNER JOIN countries ON p.country_id = countries.id;';
        $cities_req = $this->pdo->prepare($sql);
        $cities_req->execute();
        $results = $cities_req->fetchAll(\PDO::FETCH_ASSOC);
        $cities = array();
        foreach($results as $result)
        {
            $cities[] = new City($result);
        }
        return $cities;
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
