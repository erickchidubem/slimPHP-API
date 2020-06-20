<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
 
require '../vendor/autoload.php';
require  '../src/BLL/SchoolBLL.php';



$app->group('/school', function () use ($app) {

      
        $app->get('/getallschool/{pageno}',function(Request $request, Response $response){
               
               $route = $request->getAttribute('route');
               $pageno = $route->getArgument('pageno');
               $sm = new SchoolBLL;
               $no_of_record_per_page = NO_OF_RECORD_PER_PAGE;
               $result = $sm->getAllSchools($no_of_record_per_page, $pageno);    
               $response->write(json_encode($result));
               return appendJsonContentType($response,$result['code']); 

        });



         $app->post('/createschool', function(Request $request, Response $response){
              if(!haveAnyEmptyParameters(array('schoolName','location'), $request, $response)){
                      $request_data = $request->getParsedBody();
                      $schoolName = $request_data['schoolName'];
                      $location = $request_data['location'];
                     
                      $sm = new SchoolBLL;
                      $result = $sm->createSchool($schoolName, $location);

                      $response->write(json_encode($result));
                      return appendJsonContentType($response,$result['code']);
                  
              }

               return appendJsonContentType($response,BAD_REQUEST);
          });


          $app->post('/updateschool', function(Request $request, Response $response){
              if(!haveAnyEmptyParameters(array('id','schoolName','location'), $request, $response)){
                      $request_data = $request->getParsedBody();
                      $id = $request_data['id'];
                      $schoolName = $request_data['schoolName'];
                      $location = $request_data['location'];
                     
                      $sm = new SchoolBLL;
                      $result = $sm->updateSchool($id,$schoolName, $location);
                     
                      $response->write(json_encode($result));
                      return appendJsonContentType($response,$result['code']);
                  
              }

               return appendJsonContentType($response,BAD_REQUEST);
          });

        

});












