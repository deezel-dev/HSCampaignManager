<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
//require_once $_SERVER['DOCUMENT_ROOT'] . "/include/lib/swiftmailer/swift_required.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";
header("Content-Type: text/json");
$customer_data = json_decode(file_get_contents("php://input"));

$id = $customer_data->{"id"};
$email = $customer_data->{"email"};
$accepts_marketing = $customer_data->{"accepts_marketing"};
$created_at = $customer_data->{"created_at"};
$updated_at = $customer_data->{"updated_at"};
$first_name = $customer_data->{"first_name"};
$last_name = $customer_data->{"last_name"};
$orders_count = $customer_data->{"orders_count"};
$state = $customer_data->{"state"};
$total_spent = $customer_data->{"total_spent"};
$last_order_id = $customer_data->{"last_order_id"};
$note = $customer_data->{"note"};
$verified_email = $customer_data->{"verified_email"};
$multipass_identifier = $customer_data->{"multipass_identifier"};
$tax_exempt = $customer_data->{"tax_exempt"};
$phone = $customer_data->{"phone"};
$tags = $customer_data->{"tags"};
$last_order_name = $customer_data->{"last_order_name"};
$addresses = $customer_data->{"addresses"};
//{"id":706405506930370084,"email":"bob@biller.com","accepts_marketing":true,"created_at":null,"updated_at":null,"first_name":"Bob","last_name":"Biller","orders_count":0,"state":"disabled","total_spent":"0.00","last_order_id":null,"note":"This customer loves ice cream","verified_email":true,"multipass_identifier":null,"tax_exempt":false,"phone":null,"tags":"","last_order_name":null,"addresses":[]}


$campaign_added = addCampaign($email, $note, "", $created_at, $updated_at);
if($campaign_added){//$campaign_added
    //echo(json_encode($campaign_added));
    echo($campaign_added);
}
function addCampaign($campaign_name, $campaign_description, $campaign_manager, $start_date, $end_date) {
    $response = false;
    $sql = "INSERT INTO schema_CampaignManager.HSCampaignManager_hdr(
           campaign_name,
           campaign_description,
           campaign_manager,
           start_date,
           end_date,
           campaign_score,
           date_added)
     VALUES (" .
           "'" . padSql($campaign_name) . "'," .
           "'" . padSql($campaign_description). "'," .
           "'" . padSql($campaign_manager) . "'," .
           "'" . $start_date . "'," .
           "'" . $end_date ."'," .
           0 . "," .
           "" . "GETDATE()" .")";
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
