<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';



function search($subject, $title, $rating, $minlength, $maxlength, $minage, $maxage, $suggested_by) {
    $data = getDatabase();
    
    if (!$data->open())
        return "Error 2039";
    
    $where = "";
    if (isset($subject)){
         $where = $where .
             "AND (subject_ref.subject_name = '" . mysql_real_escape_string($subject) .
             "' OR parent_subject.subject_name = '" . mysql_real_escape_string($subject) . "')";
    }
    if (isset($title)) {
         $where = $where . "AND title = '" . mysql_real_escape_string($title) . "' ";
    }
    if (isset($rating)){
         $where = $where . "AND rating = '" . mysql_real_escape_string($rating) . "' ";
    }
    if (isset($minlength)){
         $where = $where . "AND length >=" . (int)($minlength) . " ";
    }
    if (isset($maxlength)){
         $where = $where . "AND length <=" . (int)($maxlength) . " ";
    }
    if (isset($minage)){
         //$where = $where . "AND minimum_age_from_rating(rating) <= " . (int)($minage) . " ";
         $where = $where . "AND age_min <= " . (int)($minage) . " ";
    }
    //if (isset($maxage)){
    //     $where = $where . "AND minimum_age_from_rating(rating) <= " . mysql_real_escape_string($maxagemysql_real_escape_string) . " ";
    //}
    if (isset($suggested_by)){
         $where = $where . "AND product_subjects.suggested_by = '" . mysql_real_escape_string($suggested_by) . "' ";
    }
    
    $data->close();
    
    if(strlen($where)>0){
        $value = _searchMovies("WHERE " . substr($where, 4, -1));
    } else {
        $value = _searchMovies("");
    }
    
    return $value;
}

function searchPlaylist($profile_id) {
    
    $data = getDatabase();
    
    if (!$data->open())
        return "Error 2039";

    $data->close();
    
    $value = getPlaylistMovies($profile_id);
    
    return $value;
}

function searchRemixSubmissions($profile_id) {
    
    $data = getDatabase();
    
    if (!$data->open())
        return "Error 2039";

    $data->close();
    
    $value = getRemixMovies($profile_id);
    
    return $value;
}


function getSubjects() {
    $sql =
        "SELECT
            currentTopic.subject_id,
            CASE WHEN parentTopic.subject_id IS NULL THEN ucase(currentTopic.subject_name)
                 ELSE currentTopic.subject_name
                 END subject_name,
            parentTopic.subject_id parent_id
        FROM
            subject_ref currentTopic
            LEFT JOIN subject_ref parentTopic ON
                currentTopic.parent_id = parentTopic.subject_id
        ORDER BY
            CASE WHEN parentTopic.subject_id IS NULL THEN currentTopic.subject_name
                 ELSE parentTopic.subject_name
                 END ASC,
            CASE WHEN parentTopic.subject_id IS NULL THEN ''
                 ELSE currentTopic.subject_name
                 END ASC";
    
    $context = getDatabase();
    
    if ($context->open()) {
        $results = $context->getData($sql);
        $myArr = array();
        foreach ($results as $row) {
            if($row['subject_name']!=null) {
                $myArr[] = array(
                    "id" => intval($row['subject_id']),
                    "name" => $row['subject_name'],
                    "parent_id" => intval($row["parent_id"])
                );
            }
        }
        return $myArr;
    }
    
    return array();
}



function getTopSubjects() {    
    $context = getDatabase();
    
    $sql =
        "SELECT
            currentTopic.subject_id,
            currentTopic.subject_name
        FROM
            subject_ref currentTopic
        WHERE
            currentTopic.parent_id IS NULL OR currentTopic.parent_id = 0
        ORDER BY
            currentTopic.subject_name ASC";
    
    if ($context->open()) {
        $results = $context->getData($sql);
        $myArr = array();
        foreach ($results as $row) {
            if($row['subject_name']!=null) {
                $myArr[] = array(
                    "id" => intval($row['subject_id']),
                    "name" => $row['subject_name'],
                    "parent_id" => null
                );
            }
        }
        return $myArr;
    }
    
    return array();
}



function getChildSubjects($parent_id, $includeAny) {
    $sql =
        "SELECT
            currentTopic.subject_id,
            currentTopic.subject_name
        FROM
            subject_ref currentTopic
        WHERE
            currentTopic.parent_id = " . (int)$parent_id . "
        ORDER BY
            currentTopic.subject_name ASC";
    
    $context = getDatabase();
    
    if ($context->open()) {
        $results = $context->getData($sql);
        $myArr = array();
        
        if ($includeAny)
            $myArr[] = array(
                "id" => 0,
                "name" => "Any",
                "parent_id" => $parent_id
            );
        
        foreach ($results as $row) {
            if($row['subject_name']!=null) {
                $myArr[] = array(
                    "id" => intval($row['subject_id']),
                    "name" => $row['subject_name'],
                    "parent_id" => $parent_id
                );
            }
        }
        return $myArr;
    }
    
    return array();
}



function _getAgeMin($rating){
    $agelow = 1;
    switch ($rating) {
        case "PG-13" : $agelow = 13;break;
        case "NC-17" : $agelow = 18;break;
        case "PG" : $agelow = 8;break;
        case "G" : $agelow = 3;break;
        case "R" : $agelow = 17;break;
        case "NR" : $agelow = 1;break;
        case "TV-Y" : $agelow = 3;break;
        case "TV-Y7" : $agelow = 7;break;
        case "TV-PG" : $agelow = 8;break;
        case "TV-G" : $agelow = 3;break;
        default : $validrating = true;break;
    }
    return $agelow;
}



function _searchMovies($whereString) {

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
        g.grade
        
        FROM product 
        
        INNER JOIN " . 
        getSQL_flix_grade($paddedScore) . " ON g.product_id = product.product_id
        
        INNER JOIN product_subjects ON product_subjects.product_id = product.product_id
        INNER JOIN subject_ref ON subject_ref.subject_id= product_subjects.subject_id
        LEFT JOIN subject_ref parent_subject ON subject_ref.parent_id = parent_subject.subject_id ";
    
    if(strlen($whereString)>0){
        $sql = $sql . $whereString;
    }
    $sql = $sql . " ORDER BY title";
    
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
                        "subject_name" => null,
                        "product_id" => $row['product_id'],
                        "description" => $row['description'],
                        "status_id" => $row['status_id'],
                        "age_min" => intval($row['age_min']),
                        "age_max" => intval($row['age_max']),
                        "product_image" => $row['product_image'],
                        "tmdb_id" => null,
                        "subject_id" => null,
                        "suggested_by" => null,
                        "grade" => getLetterGrade(intval($row['grade'])), //getGrade($row['product_id']),
                        "remixes" => getFlixRemixes($row['product_id']),
                        "vendors" =>getMovieAffiliates($row['product_id'], $row['title']));
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

function getSQL_flix_remixes(){
    
    $sql = "(SELECT product_id, flix_remix_id, lesson_name 
         FROM flix_remix_header
         GROUP BY product_id) AS remix";

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

function _searchMoviesOLD($whereString) {
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
        product_image
        FROM product INNER JOIN product_subjects ON product_subjects.product_id = product.product_id
        INNER JOIN subject_ref ON subject_ref.subject_id= product_subjects.subject_id
        LEFT JOIN subject_ref parent_subject ON subject_ref.parent_id = parent_subject.subject_id ";
    
    if(strlen($whereString)>0){
        $sql = $sql . $whereString;
    }
    $sql = $sql . " ORDER BY title";
    
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
                        "subject_name" => null,
                        "product_id" => $row['product_id'],
                        "description" => $row['description'],
                        "status_id" => $row['status_id'],
                        "age_min" => intval($row['age_min']),
                        "age_max" => intval($row['age_max']),
                        "product_image" => $row['product_image'],
                        "tmdb_id" => null,
                        "subject_id" => null,
                        "suggested_by" => null,
                        "grade" => getGrade($row['product_id']),
                        "vendors" =>getMovieAffiliates($row['product_id'], $row['title']));
                }
            }
            $results = array('movies' => $myArr);
        }
    }
    return $results;
}

function getPlaylistMovies($profileId) {
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
        user_playlist.profile_id
        FROM user_playlist INNER JOIN product ON user_playlist.product_id = product.product_id INNER JOIN product_subjects ON product_subjects.product_id = product.product_id
        INNER JOIN subject_ref ON subject_ref.subject_id= product_subjects.subject_id
        LEFT JOIN subject_ref parent_subject ON subject_ref.parent_id = parent_subject.subject_id 
        WHERE user_playlist.profile_id = " . $profileId;
    
    $sql = $sql . " ORDER BY title";
    
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
                        "subject_name" => null,
                        "product_id" => $row['product_id'],
                        "description" => $row['description'],
                        "status_id" => $row['status_id'],
                        "age_min" => intval($row['age_min']),
                        "age_max" => intval($row['age_max']),
                        "product_image" => $row['product_image'],
                        "tmdb_id" => null,
                        "subject_id" => null,
                        "suggested_by" => null,
                        "grade" => getGrade($row['product_id']),
                        "vendors" =>getMovieAffiliates($row['product_id'], $row['title']));
                }
            }
            $results = array('movies' => $myArr);
        }
    }
    return $results;
}

function getRemixMovies($profileId) {
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
        flix_remix_header.profile_id
        FROM flix_remix_header INNER JOIN product ON flix_remix_header.product_id = product.product_id INNER JOIN product_subjects ON product_subjects.product_id = product.product_id
        INNER JOIN subject_ref ON subject_ref.subject_id= product_subjects.subject_id
        LEFT JOIN subject_ref parent_subject ON subject_ref.parent_id = parent_subject.subject_id ";
    
    $sql = $sql . " ORDER BY title";
    
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
                        "subject_name" => null,
                        "product_id" => $row['product_id'],
                        "description" => $row['description'],
                        "status_id" => $row['status_id'],
                        "age_min" => intval($row['age_min']),
                        "age_max" => intval($row['age_max']),
                        "product_image" => $row['product_image'],
                        "tmdb_id" => null,
                        "subject_id" => null,
                        "suggested_by" => null,
                        "grade" => getGrade($row['product_id']),
                        "vendors" =>getMovieAffiliates($row['product_id'], $row['title']));
                }
            }
            $results = array('movies' => $myArr);
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

function getAllMovies() {

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
        g.grade
        
        FROM product 
        
        INNER JOIN " . 
        getSQL_flix_grade($paddedScore) . " ON g.product_id = product.product_id
        
        INNER JOIN product_subjects ON product_subjects.product_id = product.product_id
        INNER JOIN subject_ref ON subject_ref.subject_id= product_subjects.subject_id
        LEFT JOIN subject_ref parent_subject ON subject_ref.parent_id = parent_subject.subject_id ";

    $sql = $sql . " ORDER BY title";
    
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
                        "subject_name" => null,
                        "product_id" => $row['product_id'],
                        "description" => $row['description'],
                        "status_id" => $row['status_id'],
                        "age_min" => intval($row['age_min']),
                        "age_max" => intval($row['age_max']),
                        "product_image" => $row['product_image'],
                        "tmdb_id" => null,
                        "subject_id" => null,
                        "suggested_by" => null,
                        "grade" => getLetterGrade(intval($row['grade'])), //getGrade($row['product_id']),
                        "remixes" => getFlixRemixes($row['product_id']),
                        "vendors" =>getMovieAffiliates($row['product_id'], $row['title']));
                }
            }
            $results = array('movies' => $myArr);
        }
    }
    return $results;
}

?>