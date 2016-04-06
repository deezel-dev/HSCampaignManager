<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';
header("Content-Type: text/json");

$feedback= json_decode(file_get_contents("php://input"));

$r2 = logActivity($feedback->{"activity_type"},
               $feedback->{"product_id"},
               $feedback->{"profile_id"},
               $feedback->{"topic_id"},
               $feedback->{"data"});

function logActivity($activity_type, $product_id, $profile_id, $topic_id, $data) {
    
    $myArr = null;    

    if (!isset($_SERVER['REMOTE_ADDR'])){
            $qipAddress = "NULL";
    } else {
        $qipAddress = $_SERVER['REMOTE_ADDR'];
            
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
          $qipAddress .= "," . $_SERVER['HTTP_X_FORWARDED_FOR'];  
        }  
    }

    $sql = "INSERT INTO activity_log(activity_type, product_id, profile_id, 
                        topic_id, ip_address, data)
            VALUES (" . "'" . $activity_type . "',"  
                      . $product_id .","
                      . $profile_id ."," 
                      . $topic_id . ","
                      . "'" . $qipAddress . "'," 
                      . "'" . padSql($data) . "')";
      
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
    
    return array('flix_activity' => $myArr);
}

function padSql($input){
    return str_replace ("'","''",$input);
  }


?>