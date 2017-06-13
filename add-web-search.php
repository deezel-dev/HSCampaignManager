<?php
ini_set('display_errors', 1); error_reporting(E_ALL);
require_once 'aws_database.php';
$json = json_decode(file_get_contents("php://input"));
addSearch("DEEZEL", "12345");

function addSearch($name, $timestamp){

    $response = false;
    $sql = "INSERT INTO dbo.web_search
           (search_term
           ,search_time)

     VALUES
           (" .
                "'" . padSql($name) . "'," .
                "'" . $timestamp . "'" .
           ")";

    $data = getDatabase();
    if ($data->open()) {
        if($data->insertData($sql)){
            $response = true;
        }
    }
    return $response;
}
function padSql($subject){
    return str_replace ("'","''",$subject);
  }
?>
