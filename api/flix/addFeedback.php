<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';
header("Content-Type: text/json");

$feedback= json_decode(file_get_contents("php://input"));

$r2 = addFeedback($feedback->{"product_id"},
               $feedback->{"profile_id"},
               $feedback->{"is_flix_user"},
               $feedback->{"feedback_type"},
               $feedback->{"feedback"});

function addFeedback($product_id, $profile_id, $is_flix_user, $feedback_type, $feedback) {
    
    $myArr = null;    

    if (!isset($_SERVER['REMOTE_ADDR'])){
            $qipAddress = "NULL";
    } else {
        $qipAddress = $_SERVER['REMOTE_ADDR'];
            
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
          $qipAddress .= "," . $_SERVER['HTTP_X_FORWARDED_FOR'];  
        }  
    }
    
    $sql = "INSERT INTO flix_feedback(product_id,profile_id, is_flix_user, ip_address, feedback_type, feedback)
            VALUES (" . $product_id ."," 
                      . $profile_id ."," 
                      . $is_flix_user . ","
                      . "'" . $qipAddress . "'," 
                      . "'" . padSql($feedback_type) . "',"
                      . "'" . padSql($feedback) . "')";
      
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
    
    return array('flix_feedback' => $myArr);
}

function padSql($input){
    return str_replace ("'","''",$input);
  }


?>