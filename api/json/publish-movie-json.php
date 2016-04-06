<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requireadminsession.php";

header("Content-Type: text/json");

$json = getFlixMovies();

$fp = fopen('get-all-movies.json', 'w');
fwrite($fp, json_encode($json));
fclose($fp);

echo "json file complete!";

function getFlixMovies() {
  
    $where = "";
    $paddedScore = 10;

    $sql = "SELECT DISTINCT
        product.product_id,
        title,
        rating,
        release_date,
        rating,
        release_year,
        length,
        description,
        status_id,
        age_min,
        age_max,
        product_image,

        country,
        language,
        actors,
        director,
        writer,
        awards,
        product.type,
        imdb_id,

        g.grade
        
        FROM product 
        
        LEFT JOIN " . 
        getSQL_flix_grade($paddedScore) . " ON g.product_id = product.product_id
        
        LEFT JOIN product_subjects ON product_subjects.product_id = product.product_id
        LEFT JOIN subject_ref ON subject_ref.subject_id= product_subjects.subject_id
        LEFT JOIN subject_ref parent_subject ON subject_ref.parent_id = parent_subject.subject_id ";

        if(strlen($where)>0){
        	$sql = $sql . "WHERE " . $where;
        }

    $sql = $sql . " ORDER BY title";

    //echo $sql;
    
    $data = getDatabase();
    if ($data->open()) {
        $array = $data->getData($sql);
        if (count($array) < 1) {
            $results = "result is null";
        } else {
            //$myArr[] = null;
            foreach ($array as $row) {
                if($row['title']!=null){
                    $myArr[] = array("title" => $row['title'],
                        "length" => intval($row['length']),
                        "rating" =>$row['rating'],
                        "release_date" =>$row['release_date'],
                        "release_year" =>$row['release_year'],
                        "rating" =>$row['rating'],
                        "subject_name" => null,
                        "product_id" => $row['product_id'],
                        "description" => $row['description'],
                        "status_id" => $row['status_id'],
                        "age_min" => intval($row['age_min']),
                        "age_max" => intval($row['age_max']),
                        "product_image" => $row['product_image'], 
                                               
                        "country" => $row['country'],
                        "language" => $row['language'],
                        "actors" => $row['actors'],
                        "director" => $row['director'],
                        "writer" => $row['writer'],
                        "awards" => $row['awards'],
                        "type" => $row['type'],
                        "imdb_id" => $row['imdb_id'],

                        "grade" => getLetterGrade(intval($row['grade'])), 
                        "remixes" => getFlixRemixes($row['product_id']),
                        "vendors" =>getMovieAffiliates($row['product_id'], $row['title']),
                        "subjects" =>getSubjectsByMovie($row['product_id']),
                        
                        "tmdb_id" => null,
                        "subject_id" => null,
                        "suggested_by" => null,                        
                        
                        "submission_date" => null,
                        "movie_link" => null,
                        "ip_address" => null,
                        "submitted_by" => null,
                        "subject" => null,
                        "approved" => null,
                        "approved_date" => null,
                        "approved_by" => null,
                        "selected" => false
                        
                        );
                }
            }
            $results = array('movies' => $myArr);
        }
    }
    return $results;
}

function getSQL_flix_grade($paddedScore){
    
    $sql = "(SELECT product_id, (((SUM(score)+" . ($paddedScore-1) . ") / 
        (COUNT(product_id)+" . $paddedScore . "))*100) AS grade
         FROM flix_grading
         GROUP BY product_id) AS g";

    return $sql;

}

function getFlixRemixes($product_id) {
    $sql = "SELECT product_id, flix_remix_id, lesson_name 
         FROM flix_remix_header
         WHERE product_id = " . $product_id;

    $data = getDatabase();
    if ($data->open()) {
        $array = $data->getData($sql);
        if (count($array) < 1) {
            $results = null;
        }
        else {
            $myArr = array();
            foreach ($array as $row) {
                $prod_id = intval($row['product_id']);
                $flix_remix_id = intval($row['flix_remix_id']);
                $lesson_name = $row['lesson_name'];

                $myArr[] = array("product_id" => $product_id,
                    "flix_remix_id" => $flix_remix_id,
                    "lesson_name" => $lesson_name);
            }
            if(!empty($myArr)){
                $results = $myArr;
            } else {
                $results = "";
            }
        }
    }
    return $results;
}

function getLetterGrade($grade) {
    /*
        94 - 100 A 
        90 - 93 A- 
        87 - 89 B+ 
        83 - 86 B 
        80 - 82 B- 
        77 - 79 C+ 
        73 - 76 C 
        70 - 72 C- 
        67 - 69 D+ 
        63 - 66 D 
        60 - 62 D- 
        < 60 F

    */

    switch (TRUE) {
        case $grade>=99 : $letterGrade = 'A+';break;
        case $grade>=93 : $letterGrade = 'A';break;
        case $grade>=90 : $letterGrade = 'A-';break;
        case $grade>=87 : $letterGrade = 'B+';break;
        case $grade>=83 : $letterGrade = 'B';break;
        case $grade>=80 : $letterGrade = 'B-';break;
        case $grade>=77 : $letterGrade = 'C+';break;
        case $grade>=73 : $letterGrade = 'C';break;
        case $grade>=70 : $letterGrade = 'C-';break;
        case $grade>=67 : $letterGrade = 'D+';break;
        case $grade>=63 : $letterGrade = 'D';break;
        case $grade>=60 : $letterGrade = 'D-';break;
        case $grade<60 : $letterGrade = 'F';break;
        default :  $letterGrade = 'NOT GRADED';break;
    }
    return $letterGrade;

}

function getGrade($product_id) {

    /*

    $sql = "SELECT ((SUM(score)/COUNT(product_id))*100) AS grade
                   FROM flix_grading 
                   WHERE product_id = " . $product_id;

    */

    $paddedScore = 10;
    $total = "(COUNT(product_id)" . "+" . $paddedScore . ")";
    $score = "(SUM(score)" . "+" . ($paddedScore - 1) . ")";

    $sql = "SELECT ((". $score . "/". $total . ")*100) AS grade
                   FROM flix_grading 
                   WHERE product_id = " . $product_id;

    $data = getDatabase();
    
    if ($data->open()) {
        $array = $data->getData($sql);
        if (count($array) < 1) {
            $results = null;
        } else {
            $myArr = array();
            foreach ($array as $row) {
                $grade = intval($row['grade']);
                $letterGrade = getLetterGrade($grade);
            }            
        }
    }

    return $letterGrade;
}

function getSubjectsByMovie($product_id) {

    $sql = "SELECT product_subjects.product_id,
            product_subjects.subject_id, 
            subject_ref.subject_name, 
            subject_ref.type, 
            subject_ref.parent_id 
            FROM product_subjects INNER JOIN subject_ref 
            ON product_subjects.subject_id = subject_ref.subject_id
            WHERE product_subjects.product_id = " . $product_id . ";";

    $results = '';
    //$subjectArray[];
    $data = getDatabase();
    if ($data->open()) {
      $array = $data->getData($sql);
      if (count($array) < 1) {
        $results .= "result is null";
      } else {
        $i = - 1;
        foreach ($array as $row) {
          $i++;
          if ($row['product_id'] <> '') {
              
            $product_id = intval($row['product_id']);
            $subject_id = intval($row['subject_id']);
            $subject_name = $row['subject_name'];
            $type = intval($row['type']);
            $parent_id = intval($row['parent_id']);

            $subjectArray[] = array(
                        "subject_id" => $subject_id,
                        "subject_name" => $subject_name,
                        "type" => $type,
                        "parent_id" => $parent_id
                        );

        }
      }
    }
    }
    return $subjectArray;//array('subjects' => array_filter($subjectArray));
  }
  
function getMovieAffiliates($product_id, $title) {
    $sql = "SELECT product_vendors.product_id, product_vendors.vendor_id,
        product_vendors.vendor_product_link, product_vendors.vendor_price,
        product_vendors.vendor_isFree, vendor_ref.vendor_name, vendor_ref.vendor_logo,
        product_vendors.use_search FROM product_vendors INNER JOIN vendor_ref
        ON product_vendors.vendor_id = vendor_ref.vendor_id
        WHERE product_vendors.product_id = " . $product_id;
    $data = getDatabase();
    if ($data->open()) {
        $array = $data->getData($sql);
        if (count($array) < 1) {
            $results = null;
        }
        else {
            $myArr = array();
            foreach ($array as $row) {
                $use_search = $row['use_search'];
                $product_link = "";
                $searchString = str_replace("'", "''", $title);
                if ($row['vendor_name'] == "Amazon") {
                    if (strpos($row['vendor_product_link'],'?') !== false)
                        $product_link = $row['vendor_product_link'] . "&tag=flixa05-20";
                    else
                        $product_link = $row['vendor_product_link'] . "?tag=flixa05-20";
                } else {
                    $product_link = $row['vendor_product_link'];
                }
                $myArr[] = array("vendor_id" => $row['vendor_id'],
                    "vendor_name" => $row['vendor_name'],
                    "vendor_logo" => $row['vendor_logo'],
                    "vendor_product_link" => $product_link);
            }
            if(!empty($myArr)){
                $results = $myArr;
            } else {
                $results = "";
            }
        }
    }
    return $results;
}

?>