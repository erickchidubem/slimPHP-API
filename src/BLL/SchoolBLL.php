<?php 
    /*
        Author: Ekekwe Eric Chidubem
        Post: PHP Rest API For User Management
    */
 
    class SchoolBLL{
        
       
        //the database connection variable
        private $con; 
        function __construct(){
            require_once dirname(__FILE__) . '/_DbConnect.php';
            require_once dirname(__FILE__) .'/Pagination.php';
            $db = new DbConnect; 
            $this->con = $db->connect();
            $this->pagination = new Pagination; 
        }


        public function getAPIResponse(){
           return $this->pagination->getAPIResponse();
        }


       public function createSchool($schoolName, $location){
            $response_data = $this->pagination->getAPIResponse();
           
            $verify = $this->validateSchoolNameExistence($schoolName);
            if($verify == SCHOOL_NAME_AREADY_EXIST){
                $response_data['message'] = 'School name already exist';
            }else{
                    $handle = $this->con->prepare("INSERT INTO school(`schoolName`,`location`) values (?,?)");
                    $handle->execute([$schoolName,$location]);     
                     if($handle->rowCount()>0){
                         $response_data['error']=false; 
                         $response_data['message'] = 'School was created successfully';
                     }else{
                         $response_data['error']=true; 
                         $response_data['message'] = 'School information was not successfully created';
                     }

            }
            return $response_data;
       }

     

     public function updateSchool($id,$schoolName,$location){
            
            $response_data = $this->pagination->getAPIResponse();
            $verify = $this->validateSchoolNameExistenceMapToId($schoolName,$id);
            if($verify == SCHOOL_NAME_AREADY_EXIST){
                $response_data['message'] = 'School name already exist';
            }else{
                    $handle = $this->con->prepare("UPDATE school set schoolName = ?,location = ? WHERE id = ?");
                    $handle->execute([$schoolName,$location,$id]);     
                     if($handle->rowCount()>0){
                         $response_data['error']=false; 
                         $response_data['message'] = 'School information successfully updated';
                     }else{
                         $response_data['error']=true; 
                         $response_data['message'] = 'School information was not successfully updated';
                     }

            }

            return $response_data;
      }


     
    //This will check if school name already exists on the db
     public function validateSchoolNameExistence($schoolName){
            $handle = $this->con->prepare("SELECT * FROM school WHERE schoolName = ?");
            $handle->execute([$schoolName]);     
           
            if($handle->rowCount()>0){
               return SCHOOL_NAME_AREADY_EXIST;
            }else{
                return SCHOOL_NAME_DOES_NOT_EXIT;
            }
     }

     //This will check if there is another school that has the name you want to update
     public function validateSchoolNameExistenceMapToId($schoolName,$id){
            $handle = $this->con->prepare("SELECT * FROM school WHERE schoolName = ? AND id != ?");
            $handle->execute([$schoolName,$id]);     
          
            if($handle->rowCount()>0){
               return SCHOOL_NAME_AREADY_EXIST;
            }else{
                return SCHOOL_NAME_DOES_NOT_EXIT;
            }
     }         


     

    //this will select all schools information
    public function getAllSchools($no_of_record_per_page = NO_OF_RECORD_PER_PAGE, $pageno = 1){
        $response_data = $this->pagination->getAPIResponse();
        $sql = "SELECT * FROM school";
        $countquery = "SELECT COUNT(*) FROM school";
        $data = $this->pagination->PaginateData($sql,$countquery,$no_of_record_per_page,$pageno);
        // $query, $countquery, $no_of_record_per_page = 5, $pageno = 1
        if($data['data_count']>0){

               $response_data['error']=false; 
               $response_data['message'] = 'List of all schools';
               $response_data['data'] = $data;

         }else{

               $response_data['error']=false; 
               $response_data['message'] = 'No data exist';
               $response_data['data'] = $data;
         }

         return $response_data;
    }

    //this will 
    public function getAllSchoolsByLocation($location){

    }


}