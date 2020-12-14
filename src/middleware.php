<?php

use Tuupola\Middleware\HttpBasicAuthentication;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Firebase\JWT\JWT;
use Tuupola\Base62;
use App\Utils\Utils;

return function (App $app) {
    $container = $app->getContainer();
    $secret = $container->get('settings')['secret'];
    $_REQUEST['key'] = $secret;
    $container['logger'] = function ($c) {
        $logger = new \Monolog\Logger('my_logger');
        $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
        $logger->pushHandler($file_handler);
        return $logger;
    };
    $container['cus_logger'] = function ($c) {
        $logger = new \Monolog\Logger('my_cus_logger');
        $file_handler = new \Monolog\Handler\StreamHandler("../logs/app_custom.log");
        $logger->pushHandler($file_handler);
        return $logger;
    };
    $container['exception_logger'] =  function ($c) {
        $logger = new \Monolog\Logger('exception_logger');
        $file_handler = new \Monolog\Handler\StreamHandler("../logs/exception_logger.log");
        $logger->pushHandler($file_handler);
        return $logger;
    };

    $container["jwt"] = function ($container) {
        return new StdClass;
    };
    $app->add(function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($secret, $container) {
        //     // Use the PSR-7 $request object
        try {

            $headerValueArray = $request->getHeaders();
            // $URI = $request->getUri();
            $requestIP = $request->getServerParam('REMOTE_ADDR');
            $requestURI = $request->getServerParam('REQUEST_URI');
            $_REQUEST['requestUri'] = $requestURI;
            $method = $_SERVER['REQUEST_METHOD'];
            $requestOrigin = $request->getServerParam('HTTP_ORIGIN');
            $requestParsedBody = $request->getBody()->__toString();

            $reqData = array('type' => 'request',  'URI' => $requestURI, 'Method' => $method, 'data' => $requestParsedBody, 'requestIP' => $requestIP, 'requestOrigin' => $requestOrigin, 'headers' => $headerValueArray['HTTP_AUTHORIZATION']);
            $reqData = json_encode($reqData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            $log = $container['cus_logger'];
            $log->debug($reqData);
            try {
                if (array_key_exists("HTTP_LOCALE", $_SERVER) && $_SERVER['HTTP_LOCALE']) {
                    if ($_SERVER['HTTP_LOCALE'] == 'el') {
                        $_REQUEST['locale']  = '_GR';
                    }
                } else {
                    $_REQUEST['locale']  = '';
                }
                if (array_key_exists("HTTP_AUTHORIZATION", $headerValueArray) && !empty($headerValueArray['HTTP_AUTHORIZATION'][0])) {
                    $jwt = str_replace('Bearer ', '', $headerValueArray['HTTP_AUTHORIZATION'])[0];
                    $_REQUEST['jwt'] = $jwt;

                    $jwtData = JWT::decode($jwt, $secret, array('HS256'));
                    if (isset($jwtData->user)) {
                        $dec = Utils::safeDecrypt($jwtData->user, sodium_hex2bin($_REQUEST['key'], '')); //decrypts encoded string generated via safeEncrypt function 
                        $dec = json_decode($dec, true); //only use when you encrypted an object instead of string
                        $_REQUEST['JWT_Decoded_User']  = $dec;
                    }
                }
            } catch (\Exception $e) {
                $resData = array('type' => 'error', 'URI' => $requestURI, 'exception' => $e->getMessage(), 'headers' => $headerValueArray['HTTP_AUTHORIZATION']);
                $resData = json_encode($resData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                $log->error($resData);
            }
            $response = $next($request, $response);
            $parsedBody = $response->getBody()->__toString();
            $resData = array('type' => 'response', 'URI' => $requestURI, 'data' => $parsedBody, 'headers' => $headerValueArray['HTTP_AUTHORIZATION']);
            $resData = json_encode($resData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            $log->debug($resData);
        } catch (Exception $e) {
            $resData = array('type' => 'error',  'URI' => $requestURI, 'exception' => $e->getMessage(), 'headers' => $headerValueArray['HTTP_AUTHORIZATION']);
            $resData = json_encode($resData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            $log->error($resData);
            return $response->withStatus(500)
                ->withHeader('Content-Type', 'text/html')
                ->write('Something went wrong!' . $e);
        }

        return $response;
    });


    $app->add(new Tuupola\Middleware\JwtAuthentication([
        "path" => "/",
        "logger" => $container['logger'],
        "secret" => $secret,
        "secure" => true,
        "relaxed" => [
            "localhost",
            "api-staging.msmarriage.com",
            "api-www.msmarriage.com",
            
        ],
        "rules" => [
            new Tuupola\Middleware\JwtAuthentication\RequestPathRule([
                "path" => "/",
                "ignore" => [
                    //login
                    "/users/authenticate",
                    "/users/logout",
                    ////for new service request without login
                    // "/customers/all",
                    // "/brands/all",
                    // "/customers/(\d*)/types/all",
                    // "/customers/types/pivot/(\d*)/problems/all",
                    // "/equipment_types/all",
                    // "/locations/filter",
                    // "/service_request/serviceRequestLog",
                    //"/checkConnection",
                    //"/noLogin",
                    //"/dashboard"
                    "/articles",
                    "/candidates"
                ]
            ]),
            new Tuupola\Middleware\JwtAuthentication\RequestMethodRule([
                "passthrough" => ["OPTIONS"]
            ]),
        ],
        "callback" => function ($request, $response, $arguments) use ($container) {
            $container["jwt"] = $arguments["decoded"];
            // $a = 'sss';
        },
        "error" => function ($response, $arguments) {
            $data["status"] = "error";
            $data["message"] = $arguments["message"];
            return $response
                ->withHeader("Content-Type", "application/json")
                ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    ]));
};
