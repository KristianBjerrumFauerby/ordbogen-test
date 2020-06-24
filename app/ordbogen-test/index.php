<?php
require_once(__DIR__.'/auth.php');
require_once(__DIR__.'/utility.php');
require_once(__DIR__.'/database.php');
require_once(__DIR__.'/user_management.php');


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, origin, authorization");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");



class api{
    use json;
    use apiValidation;
    private $auth;
    private $method;
    private $request;
    private $input;
    
    public function __construct(){
        $this->auth = new auth();
        $this->setRequestInfo();
    }
    

    function handleRequest(){
       
        if($this->request == "login"){
            $retval = $this->handleLogin();     
        }elseif($this->request == "register"){
            
            $retval = $this->handleRegister();
        }else{
            
            if($this->auth->validateToken()){ 
               $database = database::dbConnection();
               $database->prepairQuery($database::getRequest);
               $database->executeQueryWithParams('ss',array($this->method,$this->request));
               $data = $database->getResultSet();
             
               if($this->checkInputKeys($this->input,$this->jsonDecode($data[0]['required']))){
                   
                   $retval = $this->classLoader($data[0], $this->input);
               }
               
             
            }	
        }
        return $retval;
    }
    private function setRequestInfo(){
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->request = ltrim($_SERVER['PATH_INFO'],'/');
        $this->input = json_decode(file_get_contents('php://input'),true);
    }
    private function handleLogin(){
        if($this->input['email'] != "" && $this->input['email'] != NULL){
            if($this->auth->validateUser($this->input['email'],$this->input['password'])){ 
                $retval = $this->jsonEncode(array("response"=>array("token"=>$this->auth->generateToken($this->input['email']))));
            }else{
                $retval = $this->jsonEncode(array("response"=>"ERROR"));
            }
        }else{
        }
        return $retval;
    }
    private function handleRegister(){
        
        if($this->input['email'] != "" && $this->input['email'] != NULL){
            $userMgm = new user_management();
            $retval = $userMgm->createUser($this->input);
        }else{
            $retval = $this->jsonEncode(array("response"=>"ERROR"));
        }
        return $retval;
    }
}

$x = new api();
$retval = $x->handleRequest();
echo $retval;

?>