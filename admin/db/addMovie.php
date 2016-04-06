<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requireadminsession.php";
header("Content-Type: text/json");

$movie = json_decode(file_get_contents("php://input"));

$r2 = addMovie($movie->{"product_id"},
               $movie->{"title"},
               $movie->{"rating"},
               $movie->{"release_date"},
               $movie->{"release_year"},
               $movie->{"length"},
               $movie->{"description"},
               $movie->{"status_id"},
               $movie->{"age_min"},
               $movie->{"age_max"},
               $movie->{"product_image"},
               $movie->{"country"},
               $movie->{"language"},
               $movie->{"actors"},
               $movie->{"director"},
               $movie->{"writer"},
               $movie->{"awards"},
               $movie->{"type"},
               $movie->{"imdb_id"});


function addMovie($product_id, $title, $rating, $release_date, $release_year, $length, $description, $status_id,
                  $age_min, $age_max, $product_image, $country, $language, $actors, $director, $writer, $awards, $type, $imdb_id) {
    
    
    $response = null;
    $success = false;
    
    //$product_id = getLastProductId();
    
    $sql = "INSERT INTO product(product_id, title, rating, release_date, release_year, length, description, age_min, age_max,
    product_image, status_id, country, language, actors, director, writer, awards, type, imdb_id)
            VALUES (" . $product_id .", 
                    '" . padSql($title) ."',
                    '" . padSql($rating) ."',
                    '" . padSql($release_date) ."',
                    '" . padSql($release_year) ."',
                    " . $length .",
                    '" . padSql($description) ."',
                    " . $age_min .",
                    " . $age_max .",
                    '" . padSql($product_image) ."',
                    1,
                    '" . padSql($country) ."',
                    '" . padSql($language) ."',
                    '" . padSql($actors) ."',
                    '" . padSql($director) ."',
                    '" . padSql($writer) ."',
                    '" . padSql($awards) ."',
                    " . padSql($type) .",
                    '" . padSql($imdb_id) ."')";
    
      
    $data = getDatabase();
    
    if ($data->open()) {
        if($data->insertData($sql)){
            $success = true;
        }
    } else {        
        
    }
    
    echo $product_id;

}


function padSql($subject){
    return str_replace ("'","''",$subject);
  }

function getLastProductId() {

    $sql = "SELECT MAX(product_id) AS maxId FROM product;";

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
          if ($row['maxId'] <> '') {

            $maxId = intval($row['maxId']);
            $nextVal =  $maxId + 1;
            return  $nextVal;

        }
      }
    }
    }
    return -1;
  }



?>