<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
//require_once $_SERVER['DOCUMENT_ROOT'] . "/include/lib/swiftmailer/swift_required.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";
header("Content-Type: text/json");

$campaign = json_decode(file_get_contents("php://input"));

$campaign_name = $campaign->{"campaign_name"};
$campaign_description = $campaign->{"campaign_description"};
$campaign_manager = $campaign->{"campaign_manager"};
$start_date = $campaign->{"start_date"};
$end_date = $campaign->{"end_date"};

$campaign_added = addCampaign($campaign_name, $campaign_description, $campaign_manager, $start_date, $end_date);

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
