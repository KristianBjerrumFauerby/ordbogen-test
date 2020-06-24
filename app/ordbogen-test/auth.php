<?php
    //include 'utility.php';
    //include 'database.php';
    require_once(__DIR__.'/utility.php');
    require_once(__DIR__.'/database.php');
    class auth {
        use json;
        use sysconfig;
        use headers;
        
        private $config;
		
	function __construct(){
            $this->config = $this->getConfig()->token;
	}
	function validateUser($username, $password){

            $database = database::dbConnection();
            $database->prepairQuery($database::validateUser);
            $database->executeQueryWithParams('ss',array($username,$password));
            $res = $database->getRowCount();

            if($res == 1){
                return true;
            }else{
                return false;
            }
            
	}
        function validateToken(){
            
            $tokenArr = $this->explodeToken();
            $secret = $this->getHashToken($tokenArr[0],$tokenArr[1]);
                               
            if($secret == $tokenArr[2]){
                
                if($this->getTokenBodyValues()->ttl > time()){
                    return true;
                }
                return false;
            }
            return false;
	}
	function generateToken($username){
            
            $header = $this->getTokenHeader();
            $body = $this->getTokenBody($username);
            $signature = $this->getHashToken($header, $body);
            $jwt = "Bearer " . $header . "." . $body . "." . $signature;
            return $jwt;
	}
        public function getTokenBodyValues(){
            
            $tokenArr = $this->explodeToken();
            $bodyObj = $this->jsonDecode(base64_decode($tokenArr[1]));
            return $bodyObj;
        }
        private function getHashToken($header,$body){
            
            switch(strtolower($this->config->algoritme)){
                case "sha256": 
                       return str_replace(['+', '/', '='], ['-', '_', ''],base64_encode(hash_hmac('sha256', $header . "." . $body, $this->config->secret, true)));
                break;  
            }
        } 
        private function explodeToken(){
            
            $token = $this->getHeader("Authorization");
            $tokenArr = explode(' ',$token);
            $tokenArr = explode('.',$tokenArr[1]);
            return $tokenArr;
        }
        private function getTokenHeader(){
            
            return str_replace(['+', '/', '='], ['-', '_', ''],base64_encode($this->jsonEncode(array('typ' => $this->config->type, 'alg' => $this->config->algoritme))));
        }
        private function getTokenBody($username){
            
            $ttl = time() + $this->config->ttl;
            return str_replace(['+', '/', '='], ['-', '_', ''],base64_encode($this->jsonEncode(array("user_login"=>$username, "ttl"=>$ttl))));
        }
        
        
    }
?>