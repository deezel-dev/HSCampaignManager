<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
//require_once $_SERVER['DOCUMENT_ROOT'] . "/include/lib/swiftmailer/swift_required.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";
header("Content-Type: text/json");

$sql = "";

$segment= json_decode(file_get_contents("php://input"));

$segment_name = $segment->{"segment_name"};
$segment_description = $segment->{"segment_description"};
$segment_active = $segment->{"segment_active"};
$segment_limit = $segment->{"segment_limit"};
$segment_retag = $segment->{"segment_retag"};
$segment_object_type_id = $segment->{"segment_object_type_id"};

$segment_added = addSegment($segment_name, $segment_description, $segment_active, $segment_limit, $segment_retag, $segment_object_type_id);

if($segment_added){
    echo($sql);
}

function addSegment($segment_name, $segment_description, $segment_active, $segment_limit, $segment_retag, $segment_object_type_id) {

    $response = false;

    $sql = "INSERT INTO schema_CampaignManager.HSSegmentManager (
        segment_name,
        segment_description,
        segment_active,
        segment_limit,
        segment_retag,
        object_type_id,
        date_added
      ) VALUES (" .
           "'" . padSql($segment_name) . "'," .
           "'" . padSql($segment_description). "'," .
           "" . $segment_active . "," .
           "" . $segment_limit . "," .
           "" . $segment_retag . "," .
           "" . $segment_object_type_id . "," .
           "" . "GETDATE()" . ")";

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
