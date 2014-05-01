<?php

namespace Becee\Controllers;

class Dummy
{
    public function viewAction($request)
    {
        $filesManager = $request->getManager('Files');
        $nb = (int) $request->getParamsUri('nb');
        if($nb > 0)
        {
            return new \QDE\Responses\TwigResponse('test.html.twig', array('nb' => $nb));
        }
        else
        {
            return new \QDE\Responses\RedirectResponse('home');
        }
    }
}
