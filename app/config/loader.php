<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */

$loader->registerNamespaces(
    [
        'App\Plugins' => APP_PATH . '/plugins/',
        'App\Plugins\Auth' => APP_PATH . '/plugins/auth',
        'App\Controllers' => APP_PATH . '/controllers/',
        'App\Models' => APP_PATH . '/models/',
        'App\Models\Facebook' => APP_PATH . '/models/facebook',
        'App\Base' => APP_PATH . '/base/',
    ]
);
$loader->register();
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->pluginsDir,
        $config->application->appDir . '/base',
    ]
)->register();
