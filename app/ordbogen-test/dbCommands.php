<?php

class dbCommands{
   
    private $prepairedStatement;
    private $resData;
    
    
   function __construct(){
     
    
   } 
     public function getAffectedRows(){
         return $this->prepairedStatement->affected_rows;
     }
     public function getRowCount(){
         $this->fetchData();
         return $this->prepairedStatement->num_rows();
     }
     public function executeQueryWithParams($types = null,$params = array()){

        $this->bindParams($types,$params); 
        $this->prepairedStatement->execute(); 
        //$this->fetchData();
     }
     public function executeQuery(){ 
        $this->prepairedStatement->execute(); 
        //$this->fetchData();
     }
     public function getResultSet(){
         $this->fetchData();
         return $this->resData;
     }
     private function fetchData(){
        $meta = $this->prepairedStatement->result_metadata(); 

        while ($field = $meta->fetch_field()) { 
            $var = $field->name; 
            $$var = null; 
            $parameters[$field->name] = &$$var; 
        }

        call_user_func_array(array($this->prepairedStatement, 'bind_result'), $parameters); 
        $hits = array();
        
        while($this->prepairedStatement->fetch()) 
        { 
            foreach($parameters as $key => $val) { 
                $c[$key] = $val; 
            } 
            $hits[] = $c; 
        }
        
        $this->resData = $hits;
     }
     public function prepairQuery($sql){
         $this->prepairedStatement = $this->dbConn->prepare($sql);
     }
     private function bindParams($types,$params){
         if($types&&$params){
            $bind_names[] = $types;
            for ($i=0; $i<count($params);$i++){
                $bind_name = 'bind' . $i;
                $$bind_name = $params[$i];
                $bind_names[] = &$$bind_name;
            }
            call_user_func_array(array($this->prepairedStatement,'bind_param'),$bind_names);
        }
     }
}


