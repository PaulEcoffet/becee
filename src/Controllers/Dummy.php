<?php

namespace Becee\Controllers;

class Dummy
{
    public function viewAction($request)
    {
        $filesManager = $request->getManager('Files');
        return $request->parseTemplate('test.html.twig', array('nb' => $request->getParamsUri('nb')));;
    }
}
