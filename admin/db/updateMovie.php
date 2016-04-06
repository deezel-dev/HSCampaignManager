<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requireadminsession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';
header("Content-Type: text/json");

$movie = json_decode(file_get_contents("php://input"));

$r2 = updateMovie($movie->{"product_id"},
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


function updateMovie($product_id,$title, $rating, $release_date, $release_year, $length, $description, $status_id,
                  $age_min, $age_max, $product_image, $country, $language, $actors, $director, $writer, $awards, $type, $imdb_id) {
    
    $myArr [] = array(
                "updateStatus"  =>"start");
    
    $sql = "UPDATE product SET title = '" . padSql($title) ."',
                    rating = '" . padSql($rating) ."',

                    release_date = '" . padSql($release_date) ."',
                    release_year = " . padSql($release_year) .",

                    length = " . $length .",
                    description = '" . padSql($description) ."',
                    age_max = " . $age_max .",
                    product_image = '" . padSql($product_image) ."',
                    status_id = " .$status_id . ",
                    country = '" . padSql($country) ."',
                    language = '" . padSql($language) ."',
                    actors = '" . padSql($actors) ."',
                    director = '" . padSql($director) ."',
                    writer = '" . padSql($writer) ."',
                    awards = '" . padSql($awards) ."',
                    type = " . $type .",
                    imdb_id = '" . padSql($imdb_id) ."'  
                    
                    WHERE product_id = " . $product_id .";";    
          
        
    $data = getDatabase();
    
    if ($data->open()) {
        if($data->execute($sql)){
            $myArr [] = array(
                "updateStatus"  =>"success");
        } else {
            $myArr [] = array(
                "updateStatus"  =>"error on update");
        }
    } else {        
        $myArr [] = array(
            "updateStatus"  =>"error on open");
    }
    
    return array('movieStatus' => $myArr);
}


function padSql($subject){
    return str_replace ("'","''",$subject);
  }




?>