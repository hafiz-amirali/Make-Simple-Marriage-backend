<?php
return [
	'settings' => [
		'appName' => 'default',
		'language' => 'en',
		'displayErrorDetails' => true, // set to false in production
		'dateFormat' => 'U', // Default dateFormat
		'type' => 'mySql', // Default db
		'secret' => 'df723aa7e367f4c22695c13e1467db12c1702a62fc20a47ee0c97dce6ffd85c9',

		// Renderer settings
		'renderer' => [
			'name' => 'default',
			'template_path' => __DIR__ . '/../templates/',
		],

		// Monolog settings
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
			// 'username' => 'dbuser',
			// 'password' => 'dbpwd',
			'charset' => 'utf8mb4',
			'collation' => 'utf8mb4_general_ci',
		]
		// 'db' => [
		// 	'driver' => 'sqlsrv',
		// 	'host' => 'localhost\SQLEXPRESS',
		// 	'database' => 'msmarriage',
		// 	'username' => 'sa',
		// 	'password' => '1234'
		// ]
	],
];
