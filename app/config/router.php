<?php

$router = $di->getRouter();

// Define your routes here
$lang = '[a-z]{2}'; // language
$s = '[a-zA-Z0-9_-]+'; // string

$router->add(
    "/",
    [
        "controller" => "index",
        "action"     => "index",
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
        "action"     => "notFound",
    ]
);

$router->handle();

