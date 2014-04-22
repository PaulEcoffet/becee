<?php

namespace Becee\Controllers;

class HttpError
{
    public function error404Action($request)
    {
        $url = $request->getParamsUri('url');
        $request->parseTemplate('HttpError/404.html.twig', array('url' => $url));
    }
}
