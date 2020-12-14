<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

$uri = $_SERVER['REQUEST_URI'];

$settingsObj = getSettings();

// Instantiate the app
$app = new \Slim\App($settingsObj);
$_REQUEST['locale']  = '';
$_REQUEST['dateFormat'] = $settingsObj['settings']['dateFormat'];
$_REQUEST['FCM_Api_Key'] = $settingsObj['settings']['FCM_Api_Key'];

$_REQUEST['dbb'] = $settingsObj['settings']['type'];
// Set up language resources
$resources = require __DIR__ . '/../src/resources.php';
$resources($app);

// Set up dependencies
$dependencies = require __DIR__ . '/../src/dependencies.php';
$dependencies($app);

// Register middleware
$middleware = require __DIR__ . '/../src/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

$container = $app->getContainer();
$dbSettings = $container->get('settings')['db'];
$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection($dbSettings);

use \Illuminate\Events\Dispatcher;
use \Illuminate\Container\Container;

$capsule->setEventDispatcher(new Dispatcher(new Container));

$capsule->setAsGlobal();  //this is important
$capsule->bootEloquent();
$container['ArticleController'] = function ($container) {
    return new \App\Controllers\ArticleController;
};
$container['CandidateController'] = function ($container) {
    return new \App\Controllers\CandidateController;
};

// Run app
$app->run();


function getSettings()
{
    // $headerValueArray = $_REQUEST->getHeader('Accept');

    $defaultSettingsObj = require __DIR__ . '/../src/settings.php';
    $defaultSettings = $defaultSettingsObj['settings'];
    if (array_key_exists("HTTP_DB", $_SERVER) && $_SERVER['HTTP_DB']) {
        $configdir = $_SERVER['HTTP_DB'];
        $tenantSettingsPath = __DIR__ . '/../src/platforms/' . $configdir . '/settings.php';

        if (file_exists($tenantSettingsPath)) {
            $tenantSettingsObj = require $tenantSettingsPath;
            $tenantSettings = $tenantSettingsObj['settings'];

            $defaultSettings = array_replace($defaultSettings, $tenantSettings);
            $defaultSettingsObj['settings'] = $defaultSettings;
        }
    }


    return $defaultSettingsObj;
}
