<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
header("Content-Type: text/json");

$remix= json_decode(file_get_contents("php://input"));

$r2 = addFlixRemix($remix->{"flix_remix_id"},
                   $remix->{"profile_id"},
                   $remix->{"product_id"},
                   $remix->{"title"},
                   $remix->{"lesson_name"},
                   $remix->{"topic"});


function addFlixRemix($flix_remix_id, $profile_id, $product_id, 
                        $title, $lesson_name, $topic) {
    
    
    $myArr = null;    

    
    $sql = "INSERT INTO flix_remix_header(
            flix_remix_id, profile_id, product_id, 
            title, lesson_name, topic, submission_date)
            VALUES (" . $flix_remix_id . "," 
                      . $profile_id .","
                      . $product_id ."," 
                      . "'" . padSql($title) ."',"
                      . "'" . padSql($lesson_name) ."',"
                      . $topic ."," 
                      . "now())";
      
    $data = getDatabase();
    
    if ($data->open()) {
        if($data->insertData($sql)){
            //addFlixRemixDetails($details, $flix_remix_id);
            echo "insert - ok";
            $myArr = array(
                "status"  =>"success");
        } else {
            echo "error on insert";
            $myArr = array(
                "status"  =>"error on insert");
        }
    } else {        
        echo "error on open";
        $myArr = array(
            "status"  =>"error on open");
    }
    
    return array('flix_remix' => $myArr);
}

function addFlixRemixDetails($array, $flix_remix_id) {


    foreach ($array as $row) {
          $i++;
          $flix_remix_id = $flix_remix_id;
          $seq_id = intval($row['id']);
          $time = $row['time'];
          $notes = $row['commentary'];
          $link = $row['link'];
          $image = $row['image'];

    $sql = "INSERT INTO flix_remix_details(
            flix_remix_id, seq_id, time, 
            notes, link, image)
            VALUES (" . $flix_remix_id . "," 
                      . $seq_id ."," 
                      . "'" . padSql($time) ."',"
                      . "'" . padSql($notes) ."',"
                      . "'" . padSql($link) ."',"
                      . "'" . padSql($image) ."')";
      
    $data = getDatabase();
    
    if ($data->open()) {
        if($data->insertData($sql)){
            echo "insert - ok";
            $myArr = array(
                "status"  =>"success");
        } else {
            echo "error on insert";
            $myArr = array(
                "status"  =>"error on insert");
        }
    } else {        
        echo "error on open";
        $myArr = array(
            "status"  =>"error on open");
    }

          
      }

    return array('flix_remix_details' => $myArr);

  }


function padSql($subject){
    return str_replace ("'","''",$subject);
  }

?>