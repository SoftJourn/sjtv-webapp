<?php
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
mb_internal_encoding('UTF-8');

// define root path:
defined('ROOT') || define('ROOT', realpath(__DIR__ . '/../').'/');
defined('HTTP_ROOT') || define('HTTP_ROOT', ROOT.'httpdocs/');
$directory = dirname($_SERVER['SCRIPT_NAME']);
if ($directory != '/') {
  $directory.= '/';
}
defined('SERVER_HOME') || define('SERVER_HOME', 'http'.($_SERVER['HTTPS'] ? 's':'').'://'.$_SERVER['SERVER_NAME'].$directory);

// define application environment:
$env = getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production';
defined('APP_ENV') || define('APP_ENV', $env);

// application bootstrap file:
require_once(ROOT . 'Application.php');

// init and run application:
$application = new Application();
$application->bootstrap();
try{
    echo $application->handle()->getContent();
} catch (Exception $e) {
    var_dump($e);
}
