<?php
  
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(1);

//list the possible methods to be used
$possible_url = array("signUp", "addDream");
$value = "An error has occurred";
//import Database class
require_once 'database-sqlserver.php';
if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url)){
  switch ($_GET["action"]) {
    case "signUp":
        $value = signUp();
        break;
    case "addDream":
        $value = addDream();
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


function addDream(){

    $name = $_GET["name"];
    $email = $_GET["email"];
    $dateOfDream = $_GET["dateOfDream"];
    $dream = $_GET["dream"];
    //$sql = "INSERT INTO dreams(name, email, date_of_dream, dream)
            //VALUES ('" . $name . "','" . $email . "','" . $dateOfDream . "','" . $dream . "')";    
      
      $sql = "INSERT INTO dreams(name, email, date_of_dream, dream)
            VALUES ('" . 'padSql($name)' . "','" . 'padSql($email)' . "','" . '2016-04-07'. "','" . 'padSql($dream)' . "')";    
      
      
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