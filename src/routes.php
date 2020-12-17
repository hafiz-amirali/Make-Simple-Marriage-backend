<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\User;

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Http\UploadedFile;

return function (App $app) {
    $container = $app->getContainer();
    $_REQUEST['upload_directory'] = __DIR__;
    $_REQUEST['upload_directory_relative'] = '\uploads';
    $app->get('/', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");
        $args['appName'] = $container->get('settings')['appName'];
        $args['languageResources'] = $container['languageResources'];

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
    $app->get('/checkConnection', function (Request $request, Response $response, array $args) {
        return  $response->withJson(true);
    });
    $app->group('/articles', function ($app) {
        $app->get('', 'ArticleController:getAllArticles');
        $app->get('/allWithoutPagenation', 'ArticleController:getAllWithoutPagination');
        
    });

    $app->group('/candidates', function ($app) {
        $app->get('', 'CandidateController:getAllCandidates');
        $app->get('/allWithoutPagenation', 'CandidateController:getAllWithoutPagination');
        
    });
    $app->group('/contactus', function ($app) {
        $app->post('', 'ContactUsController:insertContactUS');        
    });

    
    
};
