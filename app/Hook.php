<?php

namespace QDE;

interface Hook
{
    public function __construct(\QDE\App &$app);
    public function getName();
    public function run($response);
}
