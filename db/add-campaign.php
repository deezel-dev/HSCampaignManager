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

echo $campaign_name;

//$campaign_added = addCampaign($campaign_name, $campaign_description, $campaign_manager, $start_date, $end_date);

if(false){//$campaign_added
    echo(json_encode($campaign_added));
}

function addCampaign($campaign_name, $campaign_objective, $campaign_manager, $start_date, $end_date) {
    
    $response = false;
    
    $sql = "INSERT INTO HSCampaignManager_hdr(
           campaign_name,
           campaign_objective,
           campaign_manager,
           date_added,
           start_date,
           end_date)
     VALUES (" .
           "'" . padSql($campaign_name) . "'," .
           "'" . padSql($campaign_objective). "'," .
           "'" . padSql($campaign_manager) . "'," .
           "" . "GETDATE()" . "," .
           "'" . $start_date . "'," .
           "'" . $end_date ."'')"; 

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

