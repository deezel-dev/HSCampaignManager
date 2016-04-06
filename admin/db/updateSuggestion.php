<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requireadminsession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';
header("Content-Type: text/json");

$movie = json_decode(file_get_contents("php://input"));

$r2 = updateMovieSuggestion($movie->{"productsuggestion_id"},
               $movie->{"product_id"},
               $movie->{"approved"});


function updateMovieSuggestion($productsuggestion_id, $product_id,$approved) {    
    
    $myArr = null;
    
    $sql = "UPDATE productsuggestion 
                    SET product_id = " . $product_id .",
                    approved = " . $approved .",
                    approved_date = CURDATE(),
                    approved_by = '" . $_SESSION[SESSION_PROFILE_ID] ."'  
                    
                    WHERE productsuggestion_id = " . $productsuggestion_id .";";    
          
        
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


function padSql($subject){
    return str_replace ("'","''",$subject);
  }




?>