<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => getenv('DATABASE_HOST'),
        'username'    => getenv('DATABASE_USER'),
        'password'    => getenv('DATABASE_PASS'),
        'dbname'      => getenv('DATABASE_NAME'),
        'charset'     => 'utf8',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'publicDir'      => BASE_PATH . '/public/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'        => getenv('DOMAIN'),
    ],

    'modelMetadataAdapter' => 'memory',
    'cryptKey' => getenv('CRYPT_KEY'),
    'auth' => [
        'File' => [
            'enabled' => true,
            'path' => APP_PATH. '/config/users.txt',
        ],
        'Ldap' => [
            'enabled' => false,
            'host' => '',
            'port' => 389,
            'dn' => '',
            'uid' => 'uid'
        ]
    ],
    'tokensFile' => APP_PATH . '/config/tokens.txt',
    'FCMApiKey' => getenv('FCM_API_KEY'),

]);
