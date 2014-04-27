<?php

namespace QDE\Responses;

interface Response
{
    public function run(\QDE\App $app);
}
