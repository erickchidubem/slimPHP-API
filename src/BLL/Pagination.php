<?php 
    /*
        Author: Ekekwe Eric Chidubem
        Post: PHP Rest API For Pagination
    */
 
    class Pagination{
    	 //the database connection variable
        private $con;
        private $pagination; 
        function __construct(){
            require_once dirname(__FILE__) . '/_DbConnect.php';
            $db = new DbConnect; 
            $this->con = $db->connect(); 
        }


         public function getAPIResponse(){
            $response_data = array();
            $response_data['code']=SUCCESS_OK;
            $response_data['error']=true; 
            $response_data['message'] = '';
            $response_data['data'] = null;
            return $response_data;
        }


         public function countData($countquery,$no_of_records_per_page){
               
                $result = $this->con->prepare($countquery); 
                $result->execute(); 
                $total_rows = $result->fetchColumn(); 
                $total_pages = 1;
                if($no_of_records_per_page!='all'){
                	$total_pages = ceil($total_rows / $no_of_records_per_page); 
                }
                 $res = array();
                 $res['total_rows'] =ceil($total_rows);
                 $res['total_pages'] =$total_pages;
                 return $res;
      }


     public function PaginateData( $query, $countquery, $no_of_record_per_page = NO_OF_RECORD_PER_PAGE, $pageno = 1 ) {
           
            $myArray = array();
            $sql = "";
            $total = $this->countData($countquery,$no_of_record_per_page);
           
            if ($no_of_record_per_page == 'all' ) {
                  $sql = $query;
            } else { 
            	  $offset = ( $pageno - 1 ) * $no_of_record_per_page;
                  $sql = $query . " LIMIT ".$offset.", ".$no_of_record_per_page;
            }
            
               $handle = $this->con->prepare($sql);
               $handle->execute();
               if($handle->rowCount()>0){
                   while($row = $handle->fetch(PDO::FETCH_ASSOC)){
                       $myArray[] = $row;
                    }
              }
              $response_data = array();
              $response_data['page']=ceil($pageno); 
              $response_data['limit'] = $no_of_record_per_page;
              $response_data['total_rows'] = $total['total_rows'];
              $response_data['total_pages'] = $total['total_pages'];
              $response_data['data_count'] = count($myArray);
              $response_data['data'] = $myArray;
              

              return $response_data;
      }

   }