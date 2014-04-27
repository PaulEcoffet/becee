<?php

namespace Becee\Entities;

class Country
{
    public $id;
    public $iso;
    public $name;
    public $nicename;
    public $iso3;
    public $numcode;

    public function __construct(array $data=null)
    {
    }
}
