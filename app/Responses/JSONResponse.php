<?php

namespace QDE\Responses;

class JSONResponse implements Response
{
    protected $data = null;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function run(\QDE\App $app)
    {
        return json_encode($this->data);
    }
}
