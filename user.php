<?php

use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Loader;

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
mb_internal_encoding('UTF-8');


// define root path:
defined('ROOT') || define('ROOT', __DIR__ . '/');

// define application environment:
$env = getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production';
defined('APP_ENV') || define('APP_ENV', $env);

// application bootstrap file:
// Using the CLI factory default services container
$di = new CliDI();



/**
 * Register the autoloader and tell it to register the tasks directory
 */
$loader = new Loader();

$loader->registerDirs(
    [
        __DIR__ . "/tasks",
    ]
);

$loader->register();



// Load the configuration file (if any)

$configFile = __DIR__ . "/app/config/config.php";

if (is_readable($configFile)) {
    $config = include $configFile;

    $di->set("config", $config);
}

include __DIR__ . '/app/config/loader.php';

// Create a console application
$console = new ConsoleApp();

$console->setDI($di);

$operation = $argv[1];
$user = $argv[2];
$password = $argv[3];

$data = $di->getShared('config')->auth->File;
$adapter = new \App\Plugins\Auth\File($data);

switch ($operation) {

    case 'add':
        if (!$user || !$password) {
            die('Specify username and password');
        }
        echo $adapter->addUser($user, $password) . "\n";
        break;

    case 'update':
        if (!$user || !$password) {
            die('Specify username and password');
        }
        echo $adapter->updateUser($user, $password) . "\n";
        break;

    case 'delete':
        if (!$user) {
            die('Specify username');
        }
        echo $adapter->deleteUser($user) . "\n";
        break;

    default:
        echo "Supported operations:\nadd\nupdate\ndelete\n";
        break;
}