<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
header("Content-Type: text/json");

$playlist= json_decode(file_get_contents("php://input"));

$r2 = addToUserPlaylist($playlist->{"product_id"},
               $playlist->{"profile_id"});


function addToUserPlaylist($product_id, $profile_id) {
    
    
    $myArr = null;    
 
    
    $sql = "INSERT INTO user_playlist(profile_id,product_id, submission_date)
            VALUES (" . $profile_id ."," . $product_id .",now())";
      
    $data = getDatabase();
    
    if ($data->open()) {
        if($data->insertData($sql)){
            echo "insert - ok";
            $myArr = array(
                "status"  =>"success");
        } else {
            echo "error on insert";
            $myArr = array(
                "status"  =>"error on insert");
        }
    } else {        
        echo "error on open";
        $myArr = array(
            "status"  =>"error on open");
    }
    
    return array('user_playlist' => $myArr);
}


?>