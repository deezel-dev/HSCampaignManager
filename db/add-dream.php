<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
//require_once $_SERVER['DOCUMENT_ROOT'] . "/include/lib/swiftmailer/swift_required.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";
//header("Content-Type: text/json");

$name = $_GET["name"];
$email = $_GET["email"];
$dateOfDream = $_GET["dateOfDream"];
$dream = $_GET["dream"];

//$dream = json_decode(file_get_contents("php://input"));

//$dreamOk = addDream($dream->{"name"}, $dream->{"email"}, $dream->{"date_of_dream"}, $dream->{"dream"});
$dreamOk = addDream($name, $email,$dateOfDream,$dream);

if($dreamOk){
    echo(json_encode($dreamOk));
    //sendVerificationEmail($user->{"email"});
}

function addDream($name, $email, $date_of_dream, $dream) {
    
    $response = false;
    
    $sql = "INSERT INTO dreams(name, email, date_of_dream, dream)
            VALUES ('" . padSql($name) . "','" . padSql($email) . "','" .  padSql($date_of_dream) . "','" . padSql($dream) . "')";    
      
    echo $sql;
    
    $data = getDatabase();
    echo 'db open1';
    
    if ($data->open()) {
        echo 'db open2';
        if($data->insertData($sql)){
            echo 'db insert ok';
            $response = true;
        }
    } 

    return $response;
    
}

function padSql($subject){
    return str_replace ("'","''",$subject);
  }

?>