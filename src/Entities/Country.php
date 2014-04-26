<?php

namespace Becee\Entities;

class Country
{
    public $name;
    public $id;

    public function __construct($id=null, $name=null)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
