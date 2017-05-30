<?php

$router = $di->getRouter();

// Define your routes here
$lang = '[a-z]{2}'; // language
$s = '[a-zA-Z0-9_-]+'; // string

$router->add(
    "/",
    [
        "controller" => "index",
        "action" => "index",
    ]
);

$router->add(
    "/:controller/:action/:params",
    [
        'controller' => 1,
        'action' => 2,
        'params' => 3
    ]
);

$router->notFound(
    [
        "controller" => "index",
        "action" => "notFound",
    ]
);

$api = new \Phalcon\Mvc\Router\Group();
$api->setPrefix('/api')
    ->addPost(
        '/login',
        [
            'namespace' => 'App\Controllers\Api',
            'controller' => 'login',
            'action' => 'index',
        ]
    )
//    ->addGet()
;
;
$router->mount($api);
$router->handle();

