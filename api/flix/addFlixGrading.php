<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
header("Content-Type: text/json");

$rating= json_decode(file_get_contents("php://input"));

$r2 = addFlixGrading($rating->{"product_id"},
               $rating->{"profile_id"},
               $rating->{"score"});


function addFlixGrading($product_id, $profile_id, $score) {
    
    
    $myArr = null;    
 
    
    $sql = "INSERT INTO flix_grading(product_id,profile_id, submission_date, score)
            VALUES (" . $product_id ."," . $profile_id .",now()," . $score .")";
      
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
    
    return array('product_grading' => $myArr);
}


?>