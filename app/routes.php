<?php
function create_routes()
{
    // Add your routes here
    $routes[] = new Route('lol', 'Dummy', 'view');
    $routes[] = new Route('', 'Home', 'home');

    return $routes;
}

function get_routes()
{
    static $routes = null;
    if($routes === null)
    {
        $routes = create_routes();
    }
    return $routes;
}
