<?php
require_once(__DIR__.'/database.php');
require_once(__DIR__.'/utility.php');

class user_management{
    use json;
    private $database;
    
    function __construct(){
        $this->database = database::dbConnection();
        
    }
    function getUser($input){
        $data = "ERROR";
        if(isset($input['email'])){
            $this->database->prepairQuery($this->database::getUser);
            $this->database->executeQueryWithParams('s',array($input['email']));
            $data = $this->database->getResultSet();
            
        }
        
        return json_encode(array("response"=>$data));
    }
    function createUser($input){
     
        if(
            isset($input['email']) && 
            isset($input['firstname']) &&
            isset($input['lastname']) &&
            isset($input['password'])
        ){
            
            $this->database->prepairQuery($this->database::createUser);
            $this->database->executeQueryWithParams('ssss',array($input['email'],$input['firstname'],$input['lastname'],$input['password']));
            $data = $this->database->getAffectedRows();
            if($data == 1){
                return $this->jsonEncode(array("response"=>"OK"));
            }
            return $this->jsonEncode(array("response"=>"ERROR"));
        }
    }
}


