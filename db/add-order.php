<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
//require_once $_SERVER['DOCUMENT_ROOT'] . "/include/lib/swiftmailer/swift_required.php";
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
                //"" . padSql( $customer->{"orders_count"}) . "," .
                //"'" . padSql( $customer->{"state"}) . "'," .
               // "" . padSql( $customer->{"tax_exempt"}) . "," .
                //"" . padSql( $customer->{"total_spent"}) . "," .
                //"'" . padSql( $customer->{"updated_at"}) . "'," .
                //"" . padSql( $customer->{"verified_email"}) . "" .
                //"'" . padSql( $customer->{"multipass_identifier"}) . "'," .
                //"'" . padSql( $customer->{"phone"}) . "'," .
                //"'" . padSql( $customer->{"tags"}) . "'," .
                //"'" . padSql( $customer->{"addresses"}) . "'" .    
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
