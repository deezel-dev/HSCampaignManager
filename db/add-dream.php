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

    if (!isset($_SERVER['REMOTE_ADDR']))
            $qipAddress = "NULL";
    else {
        $qipAddress = $_SERVER['REMOTE_ADDR'];
            
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $qipAddress .= "," . $_SERVER['HTTP_X_FORWARDED_FOR'];
            
    }
    
    $sql = "INSERT INTO dreams(name, email, date_of_dream, dream)
            VALUES ('" . padSql($name) ."','" . padSql($email) . "','" . $date_of_dream . "','" . padSql($dream) . "')";    
      
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