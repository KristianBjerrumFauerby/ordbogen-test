<?php
trait sysconfig{
	public function getConfig (){
		return json_decode(file_get_contents('config.json'));	
	}
}
trait json{
    public function jsonDecode($data){
	$obj = json_decode($data);
	if($this->jsonError()){
            return $obj;
	}
	return "Error decoding json";
    }
    public function jsonEncode($data){
            
        $obj = json_encode($data);
        if($this->jsonError()){
            return $obj;
        }
        return "Error encoding json";
        
    }
    public function jsonError(){
	$error = json_last_error();
	if($error === 0){
            return true;
	}
        return false;
    }
}
trait headers{
    public function getHeader($name) {
       $headers = getallheaders();
       return $headers[$name];
    }
}
trait apiValidation{
    public function checkInputKeys($input,$reqired){
        
        foreach($keys as $key=>$value){
            if(!array_key_exists($value,$input)){
                return false;
            }
        }
        return true;
    }
    public function classLoader($data,$input){
        if(file_exists(__DIR__."/".$data['file'])){
            require_once(__DIR__."/".$data['file']);
        }
        $class = new $data['class']();
        $func = $data['function'];
        
        if($input == null){
            return $class->$func();
        }else{
            return $class->$func($input);
        }
    }
}

?>