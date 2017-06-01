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
$api->setPrefix('/api');
$api->addPost(
    '/login',
    [
        'namespace' => 'App\Controllers\Api',
        'controller' => 'login',
        'action' => 'index',
    ]
);
/* settings routes */
$api->addGet(
    '/settings',
    [
        'namespace' => 'App\Controllers\Api',
        'controller' => 'settings',
        'action' => 'index',
    ]
);
$api->addPost(
    '/settings',
    [
        'namespace' => 'App\Controllers\Api',
        'controller' => 'settings',
        'action' => 'update',
    ]
);

/* items routes */
$api->addGet(
    '/items',
    [
        'namespace' => 'App\Controllers\Api',
        'controller' => 'items',
        'action' => 'index',
    ]
);

$api->addPost(
    '/items/:action',
    [
        'namespace' => 'App\Controllers\Api',
        'controller' => 'items',
        'action' => 1,
    ]
);

$api->addPut(
    '/items',
    [
        'namespace' => 'App\Controllers\Api',
        'controller' => 'items',
        'action' => 'update',
    ]
);
$api->addDelete(
    '/items',
    [
        'namespace' => 'App\Controllers\Api',
        'controller' => 'items',
        'action' => 'delete',
    ]
);
/* playlist routes*/

$api->addPost(
    '/playlist/playNow',
    [
        'namespace' => 'App\Controllers\Api',
        'controller' => 'playlist',
        'action' => 'playNow',
    ]
);
$api->addGet(
    '/playlist/playNext',
    [
        'namespace' => 'App\Controllers\Api',
        'controller' => 'playlist',
        'action' => 'playNext',
    ]
);

$router->mount($api);
$router->removeExtraSlashes(true);
$router->handle();

