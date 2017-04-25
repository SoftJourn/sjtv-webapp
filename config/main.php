<?php
/**
 * Application configuration
 */
$config = array(

  'common' => array(
    'modelMetadataAdapter' => 'memory',
    'cryptKey' => 'n2уку4K{7Nnqo7mt${#V3jD1PEKe4cd324dpz{Wky',
    'auth' => [
      'File' => [
        'enabled' => true,
        'path' => 'config/users.txt',
      ],
      'Ldap' => [
        'enabled' => false,
        'host' => '',
        'port' => 389,
        'dn' => '',
        'uid' => 'uid'
      ]
    ]
  ),

  'development' => [

  ],
  'production' => [
  ]

);
return new \Phalcon\Config(array_replace_recursive($config['common'], $config[APP_ENV]));
