<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";
header("Content-Type: text/json");

$value = getFlixRemix();

echo(json_encode($value));

function getFlixRemix() {

    $remix_id = -1;

    if (isset($_GET["remix_id"])){
        $remix_id = $_GET["remix_id"];
    }

    if($remix_id > 0){
      
      $sql = "SELECT flix_remix_id, profile_id, product_id, 
            title, lesson_name, topic, submission_date
            FROM flix_remix_header 
            WHERE flix_remix_id = " . $remix_id . ";";


    $results = '';
    $data = getDatabase();
    if ($data->open()) {
      $array = $data->getData($sql);
      if (count($array) < 1) {
        $results .= "result is null";
      } else {
        $i = - 1;
        foreach ($array as $row) {
          $i++;
          if ($row['flix_remix_id'] <> '') {

            $flix_remix_id = intval($row['flix_remix_id']);
            $profile_id = intval($row['profile_id']);
            $product_id = intval($row['product_id']);
            $title = $row['title'];
            $lesson_name = $row['lesson_name'];
            $topic = intval($row['topic']);
            $submission_date = $row['submission_date'];

            $remixArray[] = array(
                        "flix_remix_id" => $flix_remix_id,
                        "profile_id" => $profile_id,
                        "product_id" => $product_id,
                        "title" => $title,
                        "lesson_name" => $lesson_name,
                        "topic" => $topic,
                        "submission_date" => $submission_date,
                        "details" => getRemixDetails($flix_remix_id));

          }
        }
       }
    }
    }

    $results = array('flix_remix_data' => $remixArray);

    return $results;

  }

  function getRemixDetails($flix_remix_id) {

    if($flix_remix_id > 0){
      
      $sql = "SELECT flix_remix_id, seq_id, time, 
            commentary, link, image,
            commentary_type, 
            option_1, option_2, option_3, option_4,
            correct_option

            FROM flix_remix_details


            WHERE flix_remix_id = " . $flix_remix_id . ";";


    $results = '';
    $data = getDatabase();
    if ($data->open()) {
      $array = $data->getData($sql);
      if (count($array) < 1) {
        $results .= "result is null";
      } else {
        $i = - 1;
        foreach ($array as $row) {
          $i++;
          if ($row['flix_remix_id'] <> '') {

            $flix_remix_id = intval($row['flix_remix_id']);
            $seq_id = intval($row['seq_id']);
            $time = $row['time'];
            $commentary = $row['commentary'];
            $link = $row['link'];
            $image = $row['image'];            
            $commentary_type = intval($row['commentary_type']);
            $option_1 = $row['option_1'];
            $option_2 = $row['option_2'];
            $option_3 = $row['option_3'];
            $option_4 = $row['option_4'];
            $correct_option = intval($row['correct_option']);

            $remixDetailArray[] = array(
                        "flix_remix_id" => $flix_remix_id,
                        "seq_id" => $seq_id,
                        "time" => $time,
                        "commentary" => $commentary,
                        "link" => $link,
                        "image" => $image,                        
                        "commentary_type" => $commentary_type,
                        "option_1" => $option_1,
                        "option_2" => $option_2,
                        "option_3" => $option_3,
                        "option_4" => $option_4,
                        "correct_option" => $correct_option);

          }
        }
       }
    }
    }

    $results = array('remixDetailArray' => $remixDetailArray);

    return $results;

  }


?>