<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requireadminsession.php";
//list the possible methods to be used
$possible_url = array(
    
                    "getAdminStats",
                    "getSuggestions", //--
                    "getFlixMovies",
                    "getFlixMovie",
                    "getMissingDescriptions",
                    "getCustomList",
                    "getMissingLinkMovies", 
                    "getMissingSubjectMovies",
                    "getFlixMoviesSimple",
                    "getMissingDescriptionsSimple",
                    "getCustomListSimple",
                    "getMissingLinkMoviesSimple", 
                    "getMissingSubjectMoviesSimple", //--
                    "getLastProductId",
                    "getExistingSuggestions",
                    "getSubjects",
                    "getTopicsAndSubtopics",
                    "getVendors", 
                    "getSubjectsByMovie",
                    "getVendorsByMovie",
                    "getLastSubjectId",
                    "getParentTopics",
                    "markSuggestionApproved",
                    "insertLink",
                    "insertSubject", 
                    "insertNewTopic",
                    "insertNewVendor"
                     );

$value = "An error has occurred";
$token = "invalid";
$providerID = null;
$providerKey = null;
$userID = null;
$user = null;
$MISSING_LINK_CLAUSE = "product.product_id NOT IN (SELECT product_vendors.product_id FROM product_vendors)";
$MISSING_SUBJECT_CLAUSE = "product.product_id NOT IN (SELECT product_subjects.product_id FROM product_subjects)";
$MISSING_IMAGE_CLAUSE = "product_image LIKE ''";
$PRODUCT_ID_SEARCH = "product.product_id = ";

//import Database class;
if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url)){
  switch ($_GET["action"]) {
    case "getAdminStats":
        if(isAuthenticated()){
            $value = getAdminStats();
        } else {
            $value = "unathorized user";
        }
        break;
    case "getSuggestions":
        if(isAuthenticated()){
            $value = getSuggestions();
        } else {
            $value = "unathorized user";
        }
        break;
    case "getFlixMovies":
        if(isAuthenticated()){
            $value = getFlixMovies('');
        } else {
            $value = "unathorized user";
        }
        break;  
    case "getFlixMoviesSimple":
        if(isAuthenticated()){
            $value = getFlixMoviesSimple('');
        } else {
            $value = "unathorized user";
        }
        break;     
    case "getFlixMovie":
        if(isAuthenticated()){                         
            if (isset($_GET["product_id"])){
                $value = getFlixMovies($PRODUCT_ID_SEARCH . $_GET["product_id"]);
            }  
        } else {
            $value = "unathorized user";
        }
        break;    
    case "getLastProductId":
        if(isAuthenticated()){
            $value = getLastProductId();
        } else {
            $value = "unathorized user";
        }
        break;
    case "getSubjects":
        if(isAuthenticated()){
            $value = getSubjectsALL();
        } else {
            $value = "unathorized user";
        }
        break;

        
    case "getTopicsAndSubtopics":
        if(isAuthenticated()){
            $value = getTopicsAndSubtopics();
        } else {
            $value = "unathorized user";
        }
        break;

    case "insertSubject":
        if(isAuthenticated()){
            $value = insertSubject();
        } else {
            $value = "unathorized user";
        }
        break;
        //b

    case "insertLink":
        if(isAuthenticated()){
            $value = insertLink();
        } else {
            $value = "unathorized user";
        }
        break;
    case "getVendors":
        if(isAuthenticated()){
            $value = getVendors();
        } else {
            $value = "unathorized user";
        }
        break;
    case "markSuggestionApproved":
        if(isAuthenticated()){
            $value = markSuggestionApproved();
        } else {
            $value = "unathorized user";
        }
        break;
    case "getLastSubjectId":
        if(isAuthenticated()){
            $value = getLastSubjectId();
        } else {
            $value = "unathorized user";
        }
        break;
    case "insertNewTopic":
        if(isAuthenticated()){
            $value = insertNewTopic();
        } else {
            $value = "unathorized user";
        }
        break;
    case "insertNewVendor":
        if(isAuthenticated()){
            $value = insertNewVendor();
        } else {
            $value = "unathorized user";
        }
        break; 
    case "getExistingSuggestions":
        if(isAuthenticated()){
            $value = getExistingSuggestions();
        } else {
            $value = "unathorized user";
        }
        break;  
    case "getMissingLinkMovies":
        if(isAuthenticated()){
            $value = getFlixMovies($MISSING_LINK_CLAUSE);
        } else {
            $value = "unathorized user";
        }
        break;    
    case "getMissingLinkMoviesSimple":
        if(isAuthenticated()){
            $value = getFlixMoviesSimple($MISSING_LINK_CLAUSE);
        } else {
            $value = "unathorized user";
        }
        break;   
    case "getMissingSubjectMovies":
        if(isAuthenticated()){
            $value = getFlixMovies($MISSING_SUBJECT_CLAUSE);
        } else {
            $value = "unathorized user";
        }
        break; 
    case "getMissingSubjectMoviesSimple":
        if(isAuthenticated()){
            $value = getFlixMoviesSimple($MISSING_SUBJECT_CLAUSE);
        } else {
            $value = "unathorized user";
        }
        break; 
    case "getSubjectsByMovie":
        if(isAuthenticated()){            
            
            if (isset($_GET["product_id"])){
                $value = getSubjectsByMovie($_GET["product_id"]);
            }        
            
        } else {
            $value = "unathorized user";
        }
        break; 
    case "getVendorsByMovie":
        if(isAuthenticated()){            
            
            if (isset($_GET["product_id"])){
                $value = getVendorsByMovie($_GET["product_id"]);
            }        
            
        } else {
            $value = "unathorized user";
        }
        break; 
    case "getCustomList":
        if(isAuthenticated()){               
            if (isset($_GET["where"])){
                $value = getFlixMovies($_GET["where"]);
            }               
        } else {
            $value = "unathorized user";
        }
        break; 
    case "getCustomListSimple":
        if(isAuthenticated()){               
            if (isset($_GET["where"])){
                $value = getFlixMoviesSimple($_GET["where"]);
            }               
        } else {
            $value = "unathorized user";
        }
        break; 
    case "getParentTopics":
        if(isAuthenticated()){               
            $value = getParentTopics();                           
        } else {
            $value = "unathorized user";
        }
        break; 
    }
    }

function isAuthenticated(){
    $authenticUser = false;
    $sql = "SELECT
            provider_user.PROVIDER_ID,
            provider_user.PROVIDER_KEY,
            provider_user.USER_ID


            FROM provider_user
            WHERE provider_user.PROVIDER_KEY='" . $_GET["api_token"] . "'";
  $data = getDatabase();
  if ($data->open()) {
    $array = $data->getData($sql);
    if (count($array) < 1) {
      $value .= "result is null";
    } else {
      //$myArr[] = null;
      foreach ($array as $row) {
        $providerID=$row['PROVIDER_ID'];
        $providerKey = $row['PROVIDER_KEY'];
        $userID = $row['USER_ID'];
      }
    }
  }
    if ($userID!=null){
      $authenticUser = true;
    }
    return $authenticUser;
}

function getAdminStats() {

    $sql = "SELECT CurrentDate, 
	   RemixCount, 
	   ProfileCount, 
	   ProspectCount, 
	   SuggestionCount,
	   UserWithPlaylist, 
	   ApprovedMovieCount, 
	   UnapprovedMovieCount

        FROM flix_stats
    
        WHERE CurrentDate IN (SELECT MAX(CurrentDate) FROM flix_stats);";

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
          if ($row['CurrentDate'] <> '') {

            $_date = $row['CurrentDate'];
            $RemixCount = intval($row['RemixCount']);
            $ProfileCount = intval($row['ProfileCount']);
            $ProspectCount = intval($row['ProspectCount']);
            $SuggestionCount = intval($row['SuggestionCount']);
            $UserWithPlaylist = intval($row['UserWithPlaylist']);
            $ApprovedMovieCount = intval($row['ApprovedMovieCount']);
            $UnapprovedMovieCount = intval($row['UnapprovedMovieCount']);

            $statArray[] = array(

                        "_date" => $_date,
                        "RemixCount" => $RemixCount,
                        "ProfileCount" => $ProfileCount,
                        "ProspectCount" => $ProspectCount,
                        "SuggestionCount" => $SuggestionCount,
                        "UserWithPlaylist" => $UserWithPlaylist,
                        "ApprovedMovieCount" => $ApprovedMovieCount,
                        "UnapprovedMovieCount" => $UnapprovedMovieCount
                        );

        }
      }
    }
    }
    return array('stats' => $statArray);
  }

function getSuggestions() {

    $sql = "SELECT productsuggestion_id, date_submitted, entry, movie_link, ip_address, profile_id, subject, approved, approved_date, approved_by
        FROM productsuggestion WHERE approved=0 OR approved IS NULL";

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
          if ($row['productsuggestion_id'] <> '') {

            $_id = $row['productsuggestion_id'];
            $title = $row['entry'];
            $submission_date = $row['date_submitted'];
            $movie_link = $row['movie_link'];
            $ip_address = $row['ip_address'];
            $submitted_by = $row['profile_id'];
            $subject = $row['subject'];
            $approved = $row['approved'];
            $approved_date = $row['approved_date'];
            $approved_by  = $row['approved_by'];

            $movieArray[] = array(

                        "_id" => $_id,
                        "title" => $title,
                        "submission_date" => $submission_date,
                        "movie_link" => $movie_link,
                        "ip_address" => $ip_address,
                        "submitted_by" => $submitted_by,
                        "subject" => $subject,
                        "approved" => $approved,
                        "approved_date" => $approved_date,
                        "approved_by" => $approved_by,
                        "selected" => false,

                        "length" => -1,
                        "rating" =>null,
                        "release_date" =>null,
                        "release_year" =>null,
                        "rating" =>null,
                        "subject_name" => null,
                        "product_id" => -1,
                        "description" => null,
                        "status_id" => null,
                        "age_min" => -1,
                        "age_max" => -1,
                        "product_image" => null,
                        "tmdb_id" => null,
                        "subject_id" => null,
                        "suggested_by" => null,
                        "product_id" => -1

                        );

        }
      }
    }
    }
    
    return $results = array('movies' => $movieArray);
  }

function getSuggestions2() {

    $sql = "SELECT productsuggestion_id, date_submitted, entry, movie_link, ip_address, profile_id, subject, approved, approved_date, approved_by
        FROM productsuggestion WHERE approved=0 OR approved IS NULL";

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
          if ($row['productsuggestion_id'] <> '') {

            $_id = $row['productsuggestion_id'];
            $title = $row['entry'];
            $submission_date = $row['date_submitted'];
            $movie_link = $row['movie_link'];
            $ip_address = $row['ip_address'];
            $submitted_by = $row['profile_id'];
            $subject = $row['subject'];
            $approved = $row['approved'];
            $approved_date = $row['approved_date'];
            $approved_by  = $row['approved_by'];

            $movieArray[] = array(

                        "_id" => $_id,
                        "title" => $title,
                        "submission_date" => $submission_date,
                        "movie_link" => $movie_link,
                        "ip_address" => $ip_address,
                        "submitted_by" => $submitted_by,
                        "subject" => $subject,
                        "approved" => $approved,
                        "approved_date" => $approved_date,
                        "approved_by" => $approved_by,
                        "selected" => false

                        );

        }
      }
    }
    }
    return $results = array('movies' => $movieArray);
  }


function getExistingSuggestions() {

    $sql = "SELECT productsuggestion_id, date_submitted, entry, movie_link, ip_address, profile_id, subject, approved, 
        approved_date, approved_by, p.title AS flixTitle, p.product_id AS flixID
        
        FROM productsuggestion AS ps, product AS p 
        WHERE ps.entry LIKE CONCAT('%', p.title, '%') AND ps.approved=0;";

    /*
        SELECT productsuggestion_id, date_submitted, entry, movie_link, ip_address, profile_id, subject, approved, 
        approved_date, approved_by, p.title, p.product_id
        
        FROM productsuggestion AS ps, product AS p 
        WHERE ps.entry LIKE CONCAT('%', p.title, '%');
        
        UPDATE productsuggestion SET 
        
        $sql = "UPDATE productsuggestion 
                    SET product_id = " . $product_id .",
                    approved = " . $approved .",
                    approved_date = '" . padSql($approved_date) ."',
                    approved_by = '" . padSql($approved_by) ."'  
                    
                    WHERE productsuggestion_id = " . $productsuggestion_id .";";
        
        
    */
    
    
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
          if ($row['productsuggestion_id'] <> '') {

            $_id = $row['productsuggestion_id'];
            $submission_date = $row['date_submitted'];
            $title = $row['entry'];
            $movie_link = $row['movie_link'];
            $ip_address = $row['ip_address'];
            $submitted_by = $row['profile_id'];
            $subject = $row['subject'];
            $approved = $row['approved'];
            $approved_date = $row['approved_date'];
            $approved_by  = $row['approved_by'];
            $flixTitle  = $row['flixTitle'];
            $flixID  = $row['flixID'];

            $movieArray[] = array(

                        "_id" => $_id,
                        "submission_date" => $submission_date,
                        "title" => $title,
                        "movie_link" => $movie_link,
                        "ip_address" => $ip_address,
                        "submitted_by" => $submitted_by,
                        "subject" => $subject,
                        "approved" => $approved,
                        "approved_date" => $approved_date,
                        "approved_by" => $approved_by,
                        "flixTitle" => $flixTitle,
                        "flixID" => $flixID,
                        "selected" => false

                        );

        }
      }
    }
    }
    return $results = $movieArray;
  }

  
  function getSubjectsALL() {

    $sql = "SELECT subject_id, subject_name, type, parent_id FROM subject_ref ORDER BY subject_name, parent_id;";

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
                        "subject_id" => $subject_id,
                        "subject_name" => $subject_name,
                        "type" => $type,
                        "parent_id" => $parent_id

                        );

        }
      }
    }
    }
    return $results = array('subjects' => $subjectArray);
  }

  function getTopicsAndSubtopics() {

    $sql = "SELECT subject_id, subject_name, type, parent_id FROM subject_ref WHERE parent_id = 0 ORDER BY subject_name;";
   

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
                        "parent_id" => $parent_id,
                        "SubTopics" => getSubTopics($subject_id)
                        );

        }
      }
    }
    }
    return $results = array('topics' => $subjectArray);
  }

  function getParentTopics() {

    $sql = "SELECT subject_id, subject_name, type, parent_id FROM subject_ref WHERE parent_id = 0 ORDER BY subject_name;";

    $topicArray[] = array("subject_id" => 0,
                        "subject_name" => "NEW PARENT TOPIC",
                        "type" => 0,
                        "parent_id" =>0);

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

            $topicArray[] = array(
                        "subject_id" => $subject_id,
                        "subject_name" => $subject_name,
                        "type" => $type,
                        "parent_id" => $parent_id
                        );

        }
      }
    }
    }
    return $results = array('topics' => $topicArray);
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

function getVendorsByMovie($product_id) {

    $sql = "SELECT
        product_vendors.product_id,product_vendors.vendor_id,product_vendors.vendor_product_link,vendor_ref.vendor_logo,vendor_ref.vendor_name
        FROM product_vendors
        INNER JOIN vendor_ref
        ON product_vendors.vendor_id = vendor_ref.vendor_id
        WHERE product_vendors.product_id = " . $product_id . ";";

    $results = '';
    $vendorArray[] = null;
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

            $vendor_id = intval($row['vendor_id']);
            $vendor_name = $row['vendor_name'];
            $vendor_logo = $row['vendor_logo'];
            $vendor_product_link = $row['vendor_product_link'];

            $vendorArray[] = array(
                        "vendor_id" => $vendor_id,
                        "vendor_name" => $vendor_name,
                        "vendor_logo" => $vendor_logo,
                        "vendor_product_link" => $vendor_product_link
                        );

        }
      }
    }
    }
    return $results = array_filter($vendorArray);
  }

  function getVendors() {

    $sql = "SELECT vendor_id, vendor_name, vendor_link, vendor_logo, vendor_search_url
            FROM vendor_ref ORDER BY vendor_name;";
      
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
          if ($row['vendor_id'] <> '') {

            $vendor_id = intval($row['vendor_id']);
            $vendor_name = $row['vendor_name'];
            $vendor_link = $row['vendor_link'];
            $vendor_logo = $row['vendor_logo'];
            $vendor_search_url = $row['vendor_search_url'];


            $vendorArray[] = array(
                        "vendor_id" => $vendor_id,
                        "vendor_name" => $vendor_name,
                        "vendor_link" => $vendor_link,
                        "vendor_logo" => $vendor_logo,
                        "vendor_search_url" => $vendor_search_url
                        );

        }
      }
    }
    }
    return $results = array('vendors' => $vendorArray);
  }

  function getSubTopics($id) {

    $sql = "SELECT subject_id, subject_name, type, parent_id FROM subject_ref WHERE parent_id = " . $id . " ORDER BY subject_name;";

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


            $subTopicArray[] = array(
                        "id" => $subject_id,
                        "name" => $subject_name,
                        "type" => $type,
                        "parent_id" => $parent_id

                        );

        }
      }
    }
    }

   
    if (!isset($subTopicArray)){
        $subTopicArray[] = null;
    }

    return $results = $subTopicArray;
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

            $myArr[] = array(
                        "status" => "success",
                        "maxId" => $maxId,
                        "nextVal" => $nextVal);

        }
      }
    }
    }
    return $results = array('last_product_id' => $myArr);
  }

  function getLastProductId_internal() {

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

  function getLastSubjectId() {

    $sql = "SELECT MAX(subject_id) AS maxId FROM subject_ref;";

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

            $myArr[] = array(
                        "status" => "success",
                        "maxId" => $maxId,
                        "nextVal" => $nextVal);

        }
      }
    }
    }
    return $results = array('last_subject_id' => $myArr);
  }



  function padSql($subject){
    return str_replace ("'","''",$subject);
  }


  function markSuggestionApproved($product_id) {

    $suggestion_id = -1;

    if (isset($_GET["suggestion_id"])){
        $suggestion_id = $_GET["suggestion_id"];
    }

    $sql = "UPDATE productsuggestion SET approved=1, product_id=" . $product_id . " WHERE productsuggestion_id=" . $suggestion_id . ";";

    $results = '';
    $data = getDatabase();
    if ($data->open()) {
      $array = $data->updateData($sql);
      if (count($array) < 1) {
        $results .= "result is null";
      } else {
        $myArr = array(
                "suggestion_id"  =>$suggestion_id,
                "product_id"  =>$product_id);
        }
    }
    return $results = array('suggeestion_approved' => $myArr);
  }

  function insertSubject(){

    //product_id, title, rating, release_date, release_year, length, description, status_id,
    //age_min, age_max, product_image, tmdb_id, subjects

    $myArr = null;

    $product_id = "";
    $subject_id = "";

    if (isset($_GET["product_id"])){
       $product_id = $_GET["product_id"];
    }

    if (isset($_GET["subject_id"])){
        $subject_id = $_GET["subject_id"];
    }

    $sql = "INSERT INTO product_subjects(product_id, subject_id)
            VALUES (" . $product_id .",
                    " . $subject_id .")";

  if (isset($_GET["product_id"])){
     $data = getDatabase();
       if ($data->open()) {
         if($data->insertData($sql)){
            $myArr = array(
                "subjects_added"  =>"sucess");
         }
      }

     return $myArr;
   } else {

      return "title not set";
   }

}


function insertLink(){

    //product_id, title, rating, release_date, release_year, length, description, status_id,
    //age_min, age_max, product_image, tmdb_id, subjects

    $product_id = "";
    $vendor_id = "";
    $vendor_product_link  ="";

    if (isset($_GET["product_id"])){
       $product_id = $_GET["product_id"];
    }

    if (isset($_GET["vendor_id"])){
        $vendor_id = $_GET["vendor_id"];
    }

    if (isset($_GET["vendor_product_link"])){
        $vendor_product_link = $_GET["vendor_product_link"];
    }

    $sql = "INSERT INTO product_vendors(product_id, vendor_id, vendor_product_link, vendor_price, vendor_isFree, use_search)
            VALUES (" . $product_id ."," . $vendor_id .",'" . $vendor_product_link ."',0,0,0)";


  if (isset($_GET["product_id"])){
     $data = getDatabase();
       if ($data->open()) {
         if($data->insertData($sql)){
            $myArr = array(
                "affiliate Link"  =>"sucess");
         }
      }

     return $myArr;
   } else {

      return "title not set";
   }

}

function insertNewTopic(){

    $results = NULL;
    $subject_id = getLastSubjectIdInternal() + 1;
    $subject_name = "";
    $type = 0;
    $parent_id = 0;

    if (isset($_GET["subject_name"])){
       $subject_name = $_GET["subject_name"];
    }

    if (isset($_GET["type"])){
       $type = $_GET["type"];
    }

    if (isset($_GET["parent_id"])){
       $parent_id = $_GET["parent_id"];
    }

    $sql = "INSERT INTO subject_ref(subject_id, subject_name, type, parent_id)
            VALUES (" . $subject_id . ",'" . $subject_name . "'," . $type . "," . $parent_id . ")";

  if (isset($_GET["subject_name"])){
     $data = getDatabase();
       if ($data->open()) {
         if($data->insertData($sql)){
            $myArr = array(
                "newSubjectEntry" =>"success",
                "subject_id"=>$subject_id,
                "subject_name"=>$subject_name,
                "type"=>$type,
                "parent_id"=>$parent_id);

            return $myArr;
         }
      }

   } else {

      return "subject not set";
   }

}

 function getLastSubjectIdInternal() {

    $sql = "SELECT MAX(subject_id) AS maxId FROM subject_ref;";

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
            return $maxId;

        }
      }
    }
    }
    return $maxId;
  }

  function insertNewVendor(){

    $vendor_id = getLastVendorId() + 1;
    $vendor_name = "";
    $vendor_link = "";
    $vendor_logo = "";
    $vendor_search_url = "";
    $active = 1;

    if (isset($_GET["vendor_name"])){
       $vendor_name = $_GET["vendor_name"];
    }

    if (isset($_GET["vendor_name"])){
       $vendor_name = $_GET["vendor_name"];
    }

    if (isset($_GET["vendor_link"])){
       $vendor_link = $_GET["vendor_link"];
    }

    if (isset($_GET["vendor_logo"])){
       $vendor_logo = $_GET["vendor_logo"];
    }

    if (isset($_GET["vendor_search_url"])){
       $vendor_search_url = $_GET["vendor_search_url"];
    }

    if (isset($_GET["active"])){
       $active = $_GET["active"];
    }


    $sql = "INSERT INTO vendor_ref(vendor_id, vendor_name, vendor_link, vendor_logo, vendor_search_url, active)
            VALUES (" . $vendor_id . ",'" . $vendor_name . "','" . $vendor_link . "','" . $vendor_logo . "','" . $vendor_search_url . "'," . $active . ")";

  if (isset($_GET["subject_name"])){
     $data = getDatabase();
       if ($data->open()) {
         if($data->insertData($sql)){
            $myArr = array(
                "newVendorEntry" =>"success",
                "vendor_id"=>$vendor_id,
                "vendor_name"=>$vendor_name,
                "vendor_link"=>$vendor_link,
                "vendor_logo"=>$vendor_logo,
                "vendor_search_url"=>$vendor_search_url,
                "active"=>$active);
         }
      }

     return $myArr;
   } else {

      return "vendor not set";
   }

}


 function getLastVendorId() {

    $sql = "SELECT MAX(vendor_id) AS maxId FROM vendor_ref;";

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
            return $maxId;

        }
      }
    }
    }
    return $maxId;
  }



function getFlixMovies($where) {
  
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

function getFlixMoviesSimple($where) {

    $sql = "SELECT 
        product_id,
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
        
        FROM product ";

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
                        "subject_name" => null,
                        "product_id" => $row['product_id'],
                        "description" => $row['description'],
                        "status_id" => $row['status_id'],
                        "age_min" => intval($row['age_min']),
                        "age_max" => intval($row['age_max']),
                        "product_image" => $row['product_image'],
                        "tmdb_id" => null,
                        "subject_id" => null,
                        "suggested_by" => null);
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

//return JSON array
exit(json_encode($value));
?>