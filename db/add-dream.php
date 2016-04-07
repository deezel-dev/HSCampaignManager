<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/lib/swiftmailer/swift_required.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";
header("Content-Type: text/json");

$dream = json_decode(file_get_contents("php://input"));

$dreamOk = addDream($dream->{"name"}, $dream->{"email"}, $dream->{"date_of_dream"}, $dream->{"dream"});

if($dreamOk){
    echo(json_encode($dreamOk));
    //sendVerificationEmail($user->{"email"});
}

function addDream($name, $email, $date_of_dream, $dream) {
    
    $response = false;
    
    $sql = "INSERT INTO dreams(name, email, date_of_dream, dream)
            VALUES ('" . 'padSql($name)' . "','" . 'padSql($email)' . "','" . '2016-04-07'. "','" . 'padSql($dream)' . "')";    
      
    $data = getDatabase();
    
    if ($data->open()) {
        if($data->insertData($sql)){
            $response = true;
        }
    } 

    return $response;
    
}

function padSql($subject){
    return str_replace ("'","''",$subject);
  }

?>