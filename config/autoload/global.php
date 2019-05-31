<?php
return [
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=archives_sys;host=localhost;port=3306',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
            PDO::MYSQL_ATTR_FOUND_ROWS => 2
        )
    ),
    'service_manager' => [
        'factories' => [
            'Zend\Db\Adapter\Adapter' => function ($container) {
                $adapterFactory = new \Zend\Db\Adapter\AdapterServiceFactory ();
                $adapter = $adapterFactory->createService($container);
                return $adapter;
            },
        ],
    ],
    'view_manager' => [
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ]
];
