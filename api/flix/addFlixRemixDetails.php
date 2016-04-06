<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
header("Content-Type: text/json");

$remix= json_decode(file_get_contents("php://input"));

$r2 = addFlixRemixDetails($remix->{"flix_remix_id"},
                   $remix->{"seq_id"},
                   $remix->{"time"},
                   $remix->{"commentary"},
                   $remix->{"link"},
                   $remix->{"image"},
                   $remix->{"commentary_type"},
                   $remix->{"option_1"},
                   $remix->{"option_2"},
                   $remix->{"option_3"},
                   $remix->{"option_4"},
                   $remix->{"correct_option"});


function addFlixRemixDetails($flix_remix_id, $seq_id, $time, $commentary, $link, $image,
                            $commentary_type,$option_1,$option_2,$option_3,$option_4,$correct_option) {

    $sql = "INSERT INTO flix_remix_details(
            flix_remix_id, seq_id, time, 
            commentary, link, image, commentary_type, 
            option_1, option_2, option_3, option_4, 
            correct_option)
            VALUES (" . $flix_remix_id . "," 
                      . $seq_id ."," 
                      . "'" . padSql($time) ."',"
                      . "'" . padSql($commentary) ."',"
                      . "'" . padSql($link) ."',"
                      . "'" . padSql($image) ."',"
                      . "" .  $commentary_type .","
                      . "'" . padSql($option_1) ."',"
                      . "'" . padSql($option_2) ."',"
                      . "'" . padSql($option_3) ."',"
                      . "'" . padSql($option_4) ."',"
                      . "" . $correct_option .")";
      
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


    return array('flix_remix_details' => $myArr);

  }


function padSql($subject){
    return str_replace ("'","''",$subject);
  }

?>