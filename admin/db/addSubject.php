<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requireadminsession.php";
header("Content-Type: text/json");

$subject= json_decode(file_get_contents("php://input"));

$r2 = addSubject($subject->{"product_id"},
               $subject->{"subject_id"});


function addSubject($product_id, $subject_id) {
    
    
    $myArr = null;    
 
    
    $sql = "INSERT INTO product_subjects(product_id,subject_id)
            VALUES (" . $product_id .", 
                    " . $subject_id .")";
      
    $data = getDatabase();
    
    if ($data->open()) {
        if($data->insertData($sql)){
            echo "insert - ok";
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


function padSql($subject){
    return str_replace ("'","''",$subject);
  }


?>