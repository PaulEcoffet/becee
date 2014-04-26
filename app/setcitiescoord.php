<?php

require 'app.php';

$app = new \QDE\App();

if ($app->getConfig()['debug'])
{
    $pdo = $app->getPdo();
    $geocoder = $app->getGeocoder();

    $cities_req = $pdo->prepare('SELECT c.id as cid, c.name as cname, countries.name as countryname FROM cities c INNER JOIN provinces p on c.province_id = p.id INNER JOIN countries on p.country_id = countries.id');
    $cities_req->execute();
    echo '<pre>';
    while($city = $cities_req->fetch())
    {
        echo '<b>'.$city['cname'].' '.$city['countryname'].'</b>'."\n";
        $geocode = $geocoder->geocode($city['cname'].' '.$city['countryname']);
        var_dump($geocode);
        $req = $pdo->prepare('UPDATE cities SET lat=:lat, lng=:lng WHERE id=:id;');
        $req->bindValue('lat', $geocode->getLatitude());
        $req->bindValue('lng', $geocode->getLongitude());
        $req->bindValue('id', $city['cid']);
        $req->execute();
    }
    echo '</pre>';
}
else
{
    echo 'This file should never be launched in production!!!';
}
