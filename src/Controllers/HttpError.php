<?php

namespace Becee\Controllers;

class HttpError
{
    public function error404Action($request, $url)
    {
        $request->parseTemplate('HttpError/404.html.link', array('url' => $url));
    }
}
