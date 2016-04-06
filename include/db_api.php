<?php
  
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(1);

//list the possible methods to be used
$possible_url = array("signUp");
$value = "An error has occurred";
//import Database class
require_once 'database-sqlserver.php';
if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url)){
  switch ($_GET["action"]) {
    case "signUp":
        $value = signUp();
        break;
    }
}

  function getDatabase(){
    $serverName = "tcp:deezel-dev.cloudapp.net";
    $connectionOptions = array("Database"=>'PropheticMinistries',
                               "UID"=>'web_user', "PWD"=>'p@$$w0rd');
    
    $data = new Database($serverName, $connectionOptions);
    //$data = new Database('deezel-dev.cloudapp.net', 'webuser', 'P@ssw0rd928', 'OneWord');
    return  $data;
  }


function signUp(){

    $email = $_GET["email"];
    $sql = "INSERT INTO prospects (email) VALUES ('" . $email . "')";
    
    $data = getDatabase();
    if ($data->open()) {
              if($data->insertData($sql)){
             $myArr = array(
                "postStatus"  => "success");
        }
    }
    
    return $myArr;
}

//return JSON array
exit(json_encode($value));
?>