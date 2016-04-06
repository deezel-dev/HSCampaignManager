<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requireadminsession.php";
header("Content-Type: text/json");

$subject= json_decode(file_get_contents("php://input"));

$r2 = removeSubject($subject->{"product_id"},
               $subject->{"subject_id"});


function removeSubject($product_id, $subject_id) {
    
    
    $myArr = null;    
 
    
    $sql = "DELETE FROM product_subjects 
            WHERE product_id =" . $product_id . " AND subject_id =" . $subject_id .";";
      
    $data = getDatabase();
    
    if ($data->open()) {
        if($data->execute($sql)){
            $myArr = array(
                "status"  =>"sucess");
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
    
    return array('product_vendorStatus' => $myArr);
}



?>