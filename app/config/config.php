<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => '',
        'username' => '',
        'password' => '',
        'dbname' => '',
        'charset' => 'utf8',
    ],
    'application' => [
        'appDir' => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir' => APP_PATH . '/models/',
        'migrationsDir' => APP_PATH . '/migrations/',
        'viewsDir' => APP_PATH . '/views/',
        'pluginsDir' => APP_PATH . '/plugins/',
        'libraryDir' => APP_PATH . '/library/',
        'cacheDir' => BASE_PATH . '/cache/',
        'publicDir' => BASE_PATH . '/public/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri' => 'https://tv.testing.softjourn.if.ua/',
    ],

    'modelMetadataAdapter' => 'memory',
    'cryptKey' => 'n2СѓРєСѓ4K{7Nnqo7mt${#V3jD1PEKe4cd324dpz{Wky',
    'auth' => [
        'File' => [
            'enabled' => false,
            'path' => APP_PATH . '/config/users.txt',
        ],
        'Ldap' => [
            'enabled' => true,
            'host' => 'ldap.softjourn.if.ua',
            'port' => 389,
            'dn' => 'ou=People,ou=Users,dc=ldap,dc=sjua',
            'uid' => 'uid'
        ]
    ],
    'tokensFile' => APP_PATH . '/config/tokens.txt',
    'FCMApiKey' => 'AAAAYef8w9E:APA91bGinlEJFzUswK1DS1xLy_JH75UkC-LGDl8SqaErErI0FRnDHkNRx0Gz0v6rn3Y-SIVDYM_5YmdeopapJwEFpS9MK5jV0BAmPaBZfPTGFPQfAdq-QxaLd3P-A9KBN2L89K2T5O8m',

]);
