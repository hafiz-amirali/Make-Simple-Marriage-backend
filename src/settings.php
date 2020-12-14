<?php
return [
    'settings' => [
        'appName' => 'default',
        'language' => 'en',
        'displayErrorDetails' => true, // set to false in production
        'dateFormat' => 'DD/MM/YYYY HH:mm:ss', // Default dateFormat
        'type' => 'mySql', // Default db
        'FCM_Api_Key' => 'AAAAfrlRKnE:APA91bEXNGfY9B8IWfsImLZ00-JKb5MzxtVPWH5dNUFQfpWJfhMs493ChP10kAxtCZNkWlRSccYS6Ng0pB4hlZrtjv8kqIbrssGeV3ZVkqwKpbxHHQXCGii0oI3nxm7IsTpQiKNJe0H3',
        'secret' => 'df723aa7e367f4c22695c13e1467db12c1702a62fc20a47ee0c97dce6ffd85c9', //sodium_bin2hex(sodium_crypto_secretbox_keygen());
        // Renderer settings
        'renderer' => [
            'name' => 'default',
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings testing again and again
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        'db' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'database' => 'msmarriage',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
        ]
        // 'db' => [
        //     'driver' => 'sqlsrv',
        //     'host' => 'localhost\SQLEXPRESS',
        //     'database' => 'msmarriage',
        //     'username' => 'sa',
        //     'password' => '1234'
        // ]
    ],
];
