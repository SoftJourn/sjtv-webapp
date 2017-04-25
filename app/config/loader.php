<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */

$loader->registerNamespaces(
    [
        'App\Plugins' => 'app/plugins/',
        'Base' => 'app/base/',
        'App'    => 'app/',
    ]
)->register();
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->pluginsDir,
        $config->application->appDir . '/base',
    ]
)->register();
