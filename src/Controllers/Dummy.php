<?php

namespace Beece\Controllers;

class Dummy
{
    public function viewAction($request)
    {
        $request->parseTemplate('test.html.link', array('nb' => 2));
    }
}
