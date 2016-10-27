<?php
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
mb_internal_encoding('UTF-8');

// define root path:
defined('ROOT') || define('ROOT', realpath(__DIR__ . '/../') . '/');
defined('HTTP_ROOT') || define('HTTP_ROOT', ROOT . 'httpdocs/');
defined('PLAYLIST_FILE') || define('PLAYLIST_FILE', HTTP_ROOT . 'playlist.json');
$directory = dirname($_SERVER['SCRIPT_NAME']);
if ($directory != '/') {
	$directory .= '/';
}
if (!$argv[1]) {
	exit('Specify Site URL');
}
defined('SERVER_HOME') || define('SERVER_HOME', $argv[1].'/');

// define application environment:
$env = getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production';
defined('APP_ENV') || define('APP_ENV', $env);

// application bootstrap file:
require_once(ROOT . 'Application.php');

// init and run application:
$application = new Application();
$application->bootstrap();


$id = 'tinypulse-result';
$playlist = new \App\Models\Playlist();

//remove old record
$playlist->removeItem($id);

//create new record
$owner = 'phantom';
$fileName = 'tinypulse-'.time().'.png';
$path = HTTP_ROOT . \App\Models\Image::$filesDir.$fileName;
$thumbPath = HTTP_ROOT . \App\Models\Image::$filesDir.'thumbs/'.$fileName;
$tinyPulseFile = __DIR__ . '/tinypulse.png';

if  (file_exists($tinyPulseFile)) {
	copy($tinyPulseFile, $path);
	$thumbnail = new \Phalcon\Image\Adapter\Imagick($path);
	$thumbnail->resize(320, 180);
	$thumbnail->save($thumbPath);

	$image = new \App\Models\Image($fileName, $owner, 20);
	$image->setID($id);

	$playlist->addItem($image, true);
	$playlist->save();
}