<?php

namespace Becee\Controllers;

class HttpError
{
    public function error404Action($request)
    {
        $url = $request->getParamsUri('url');
        return new \QDE\Responses\TwigResponse('HttpError/404.html.twig', array('url' => $url));
    }
}
