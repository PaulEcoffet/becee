<?php

namespace Becee\Controllers;

class Dummy
{
    public function viewAction($request)
    {
        return $request->parseTemplate('test.html.twig', array('nb' => 2));
    }
}
