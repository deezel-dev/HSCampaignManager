<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
//require_once $_SERVER['DOCUMENT_ROOT'] . "/include/lib/swiftmailer/swift_required.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";
header("Content-Type: text/json");
$customer = json_decode(file_get_contents("php://input"));

//$id = $customer_data->{"id"};
//$email = $customer_data->{"email"};
//$accepts_marketing = $customer_data->{"accepts_marketing"};
//$created_at = $customer_data->{"created_at"};
//$updated_at = $customer_data->{"updated_at"};
//$first_name = $customer_data->{"first_name"};
//$last_name = $customer_data->{"last_name"};
//$orders_count = $customer_data->{"orders_count"};
//$state = $customer_data->{"state"};
//$total_spent = $customer_data->{"total_spent"};
//$last_order_id = $customer_data->{"last_order_id"};
//$note = $customer_data->{"note"};
//$verified_email = $customer_data->{"verified_email"};
//$multipass_identifier = $customer_data->{"multipass_identifier"};
//$tax_exempt = $customer_data->{"tax_exempt"};
//$phone = $customer_data->{"phone"};
//$tags = $customer_data->{"tags"};
//$last_order_name = $customer_data->{"last_order_name"};
//$addresses = $customer_data->{"addresses"};
//{"id":706405506930370084,"email":"bob@biller.com","accepts_marketing":true,"created_at":null,"updated_at":null,"first_name":"Bob","last_name":"Biller","orders_count":0,"state":"disabled","total_spent":"0.00","last_order_id":null,"note":"This customer loves ice cream","verified_email":true,"multipass_identifier":null,"tax_exempt":false,"phone":null,"tags":"","last_order_name":null,"addresses":[]}


$customer_added = addCustomer($customer);
if($customer_added){
    echo($customer_added);
}

function addCustomer($customer){
    
    $response = false;
    $sql = "INSERT INTO dbo.customer_created
           (channel_customer_id
           ,email)
     VALUES
           (" . "'" . padSql( $customer->{"id"}) . "'," .  "'" . padSql( $customer->{"email"}) . "'" .  ")";
           //,accepts_marketing
           //,created_at
           //,first_name
           //,last_name
           //,last_order_id
           //,last_order_name
           //,note
           //,orders_count
           //,state
           //,tax_exempt
           //,total_spent
           //,updated_at
           //,verified_email)

     //VALUES
           //(" .
               // "'" . padSql( $customer->{"id"}) . "'," .
               // "'" . padSql( $customer->{"email"}) . "'" .
              /*  "" . padSql( $customer->{"accepts_marketing"}) . "," .
                "'" . padSql( $customer->{"created_at"}) . "'," .
                "'" . padSql( $customer->{"first_name"}) . "'," .
                "'" . padSql( $customer->{"last_name"}) . "'," .
                "'" . padSql( $customer->{"last_order_id"}) . "'," .
                "'" . padSql( $customer->{"last_order_name"}) . "'," .
                "'" . padSql( $customer->{"note"}) . "'," .
                "" . padSql( $customer->{"orders_count"}) . "," .
                "'" . padSql( $customer->{"state"}) . "'," .
                "" . padSql( $customer->{"tax_exempt"}) . "," .
                "" . padSql( $customer->{"total_spent"}) . "," .
                "'" . padSql( $customer->{"updated_at"}) . "'," .
                "" . padSql( $customer->{"verified_email"}) . "" */
                //"'" . padSql( $customer->{"multipass_identifier"}) . "'," .
                //"'" . padSql( $customer->{"phone"}) . "'," .
                //"'" . padSql( $customer->{"tags"}) . "'," .
                //"'" . padSql( $customer->{"addresses"}) . "'" .    
           // ")";
    
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
