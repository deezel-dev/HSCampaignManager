<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";
header("Content-Type: text/json");

$value = getSubjects();
echo(json_encode($value));

function getSubjects() {

    $sql = "SELECT subject_id, subject_name, type, parent_id 
    FROM subject_ref 
    WHERE parent_id=0
    ORDER BY subject_name;";

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
          if ($row['subject_id'] <> '') {

            $subject_id = intval($row['subject_id']);
            $subject_name = $row['subject_name'];
            $type = intval($row['type']);
            $parent_id = intval($row['parent_id']);
            
            $subjectArray[] = array(
                        "id" => $subject_id,
                        "name" => $subject_name,
                        "type" => $type,
                        "parent_id" => $parent_id);
        }
      }
    }
    }
    return $results = array('subjects' => $subjectArray);
  }


?>