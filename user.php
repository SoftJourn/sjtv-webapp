<?php
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
mb_internal_encoding('UTF-8');

// define root path:
defined('ROOT') || define('ROOT', __DIR__ . '/');

// define application environment:
$env = getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production';
defined('APP_ENV') || define('APP_ENV', $env);

// application bootstrap file:
require_once(ROOT . 'Application.php');

// init and run application:
$application = new Application();
$application->bootstrap();

$operation = $argv[1];
$user = $argv[2];
$password = $argv[3];

$data = $application->di->getShared('config')->auth->File;
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