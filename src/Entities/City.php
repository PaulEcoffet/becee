<?php

namespace Becee\Entities;

use Becee\Entities\Province;
use Becee\Entities\Country;

class City
{
    public $name;
    public $id;
    public $postal_code;
    public $lat;
    public $lng;
    public $province;
    public $country;

    public function __construct(array $data=null)
    {
        $this->province = new Province();
        $this->country = new Country();
        $this->province->country = &$this->country;
        foreach($data as $key=>$value)
        {
            $info_key = explode('_', $key, 2);
            if($info_key[0] === 'city')
            {
                $this->$info_key[1] = $value;
            }
            elseif($info_key[0] === 'province')
            {
                $this->province->$info_key[1] = $value;
            }
            elseif($info_key[0] === 'country')
            {
                $this->country->$info_key[1] = $value;
            }
        }
    }
}
