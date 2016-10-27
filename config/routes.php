<?php

$lang = '[a-z]{2}'; // language
$s = '[a-zA-Z0-9_-]+'; // string


$routes = [
    'default' => [
        'pattern' => "/(?:({$lang})(?:/)|)(?:/|)({$s}|)(?:/|)({$s}|)(?:/|)(/.*)*",
        'params' => [
            'lang' => 1,
            'controller' => 2,
            'action' => 3,
            'params' => 4
        ]
    ],
    'admin' => [
        'pattern' => "/(?:({$lang})(?:/)|)(?:/|)admin(?:/|)({$s}|)(?:/|)({$s}|)(?:/|)(/.*)*",
        'params' => [
            'lang' => 1,
            'admin' => "admin",
            'controller' => 2,
            'action' => 3,
            'params' => 4
        ]
    ]
];
return $routes;
