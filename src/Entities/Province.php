<?php


namespace Becee\Entities;

use Becee\Entities\Country;

class Province
{
    public $name;
    public $id;
    public $country;

    public function __construct($id=null, $name=null, &$country=null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
    }
}
