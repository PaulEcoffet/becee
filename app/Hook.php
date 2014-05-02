<?php

namespace QDE;

abstract class Hook
{
    abstract public function __construct(\QDE\App &$app);
    abstract public function getName();

    public function runDescending($response)
    {
        return $response;
    }

    public function runAscending($request)
    {
        return $request;
    }
}
