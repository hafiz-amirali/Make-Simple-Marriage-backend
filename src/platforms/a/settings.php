<?php
return [
	'settings' => [
		'appName' => 'a',
		'language' => 'es',
		'displayErrorDetails' => true, // set to false in production
		'dateFormat' => 'Y-m-d', // a's dateFormat
		'secret' => 'msmarriage_secret_!@#$',

		'db' => [
			'driver' => 'sqlsrv',
			'host' => 'DESKTOP-5VES729\SQLEXPRESS',
			'database' => 'msmarriage',
			'username' => 'sa',
			'password' => '1234'
		]
	],

];
