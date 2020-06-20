<?php 
    class DbConnect{
        
        private $con;

        function connect(){
           include_once dirname(__FILE__)  . '/_Constants.php';
        try{

            $this->con = new PDO("mysql:host=localhost;dbname=test", "root", "",
            array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_PERSISTENT=>false));
            }catch(PDOException $e){
                    echo $e->getMessage();
               return null;
            }
            return $this->con;
        }


        
    }