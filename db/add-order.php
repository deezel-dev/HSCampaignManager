<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";
header("Content-Type: text/json");
$order = json_decode(file_get_contents("php://input"));

$order_added = addOrder($order);
if($order_added){
    echo($order_added);
}

function addOrder($order){
    
    $response = false;
    $sql = "INSERT INTO dbo.order_created
           (shopifyid)

     VALUES
           (" .
                "'" . padSql( $order->{"id"}) . "'" .  
           ")";
    echo $sql;
    $data = getDatabase();
    if ($data->open()) {
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
