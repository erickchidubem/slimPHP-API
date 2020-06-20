<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;
 
require '../vendor/autoload.php';


$app = new \Slim\App([
    'settings'=>[
        'displayErrorDetails'=>true
    ]
]);


  require '../src/BLL/_Constants.php';
  require '../src/controllers/SchoolController.php';
  

function appendJsonContentType ($response,$status_code){
    return $response
        ->withHeader('Content-type', 'application/json')
        ->withHeader('Cache-Control', 'no-store,no-cache')
        ->withStatus($status_code);   
}


function haveAnyEmptyParameters($required_params, $request, $response){
    $error = false; 
    $error_params = array();
    $error_params_message = '';
    $request_params = $request->getParsedBody();
    $n = 0;
    foreach($required_params as $param){
        if(!isset($request_params[$param]) || strlen($request_params[$param])<=0){
            $error = true; 
            $error_params[$n] = $param;
            $error_params_message .= $param . ', ';
            $n++;
        }
    }
    if($error){
        $error_detail = array();
        $error_detail['error'] = true;
        $error_detail['error_model'] = $error_params; 
        $error_detail['message'] = 'Required parameters ' . substr($error_params_message, 0, -2) . ' are missing or empty';
        $response->write(json_encode($error_detail));
    }
    return $error; 
}



$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Cache-Control', 'public, max-age=0');
});

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});



$app->run();