<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
//require_once $_SERVER['DOCUMENT_ROOT'] . "/include/lib/swiftmailer/swift_required.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";
header("Content-Type: text/json");

$dream = json_decode(file_get_contents("php://input"));

$name = $dream->{"name"};
$email = $dream->{"email"};
$date_of_dream = $dream->{"date_of_dream"};
$dream = $dream->{"dream"};

//$name = $_GET["name"];
//$email = $_GET["email"];
//$dateOfDream = $_GET["date_of_dream"];
//$dream = $_GET["dream"];

$dreamOk = addDream($name, $email, $date_of_dream, $dream);

if($dreamOk){
    echo(json_encode($dreamOk));
    //sendVerificationEmail($user->{"email"});
}

function addDream($name, $email, $date_of_dream, $dream) {
    
    $response = false;
    
    $sql = "INSERT INTO dreams(name, email, date_of_dream, dream)
            VALUES ('" . padSql($name) . "',
            '" . padSql($email) . "',
            '" .  padSql($date_of_dream) . "',
            '" . padSql($dream) . "')";    
      
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