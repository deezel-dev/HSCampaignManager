<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requireadminsession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';
header("Content-Type: text/json");

$movie = json_decode(file_get_contents("php://input"));

$r2 = deleteMovie($movie->{"product_id"});


function deleteMovie($product_id) {
    
    
    $myArr = null;
    
    $sql = "DELETE FROM product WHERE product_id = " . $product_id .";";    
          
        
    $data = getDatabase();
    
    if ($data->open()) {
        if($data->execute($sql)){
            $myArr = array(
                "status"  =>"sucess");
        } else {
            $myArr = array(
                "status"  =>"error on update");
        }
    } else {        
        $myArr = array(
            "status"  =>"error on open");
    }
    
    return array('movieStatus' => $myArr);
}

?>