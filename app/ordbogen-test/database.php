<?php
require_once(__DIR__.'/utility.php');
require_once(__DIR__.'/sqlStatements.php');
require_once(__DIR__.'/dbCommands.php');
 class database extends dbCommands implements statements{
     use sysconfig;
     
     private $host;
     private $user;
     private $pass;
     private $database;
     private $port;

     
     public static $db = null;
     public $dbConn = null;
     
     function __construct(){
        
         $this->getDBconnectionsInfo();
       
         $this->setConnection();
     
     }
     public static function dbConnection(){

        if (self::$db == null){
            self::$db = new database();
        }
        return self::$db;
     }
     
     private function getDBconnectionsInfo(){
         $config = $this->getConfig()->db;
         $this->host = $config->host;
         $this->user = $config->user;
         $this->pass = $config->password;
         $this->database = $config->database;
         $this->port = $config->port;
     }
     private function setConnection(){ 
         $this->dbConn = new mysqli($this->host, $this->user, $this->pass, $this->database,$this->port);
         if ($this->dbConn->connect_error) {
            echo mysqli_connect_error();
            exit();
        }
     }
 }

?>

