<?php
require_once(__DIR__.'/database.php');
require_once(__DIR__.'/utility.php');
require_once(__DIR__.'/auth.php');

class todo{
    use json;
    private $database;
    
    function __construct(){
        $this->database = database::dbConnection();
        
    }
    function getTodos(){
        
        $this->database->prepairQuery($this->database::getTodos);
        $this->database->executeQuery();
        $data = $this->database->getResultSet();
        $retval = array();
        $timeToAdd = date('Z');
        foreach($data as $key=>$value){
            
            $value['created_at'] = date('d/m/Y H:i:s', $value['created_at'] + $timeToAdd);
            if($value['finished_at'] != NULL){
                $value['finished_at'] = date('d/m/Y H:i:s', $value['finished_at'] + $timeToAdd);
            }else{
               $value['finished_at'] = 'notdone'; 
            }
            $retval[] = $value;
        }
  
        return $this->jsonEncode(array("response"=>$retval));
    }
    function createTodo($input){

        $auth = new auth();
        $userid = $auth->getTokenBodyValues()->user_login;
        $this->database->prepairQuery($this->database::createTodo);
        $this->database->executeQueryWithParams('ss',array($userid,$input['todo']));
        $data = $this->database->getAffectedRows();
        if($data == 1){
            return $this->jsonEncode(array("response"=>"OK"));
        }
        return $this->jsonEncode(array("response"=>"ERROR"));
        //get login_info from token 
    }
    function finishTodo($input){
        $this->database->prepairQuery($this->database::finishTodo);
        $this->database->executeQueryWithParams('i',array($input['id']));
        $data = $this->database->getAffectedRows();
        if($data == 1){
            return $this->jsonEncode(array("response"=>"OK"));
        }
        return $this->jsonEncode(array("response"=>"ERROR"));
    }
}
?>
