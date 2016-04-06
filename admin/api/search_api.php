<?php

//require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
//require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
//require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requireadminsession.php";

  $possible_url = array("getAmazonData","getGoogleImages","getYouTubeVideos");
  $value = "An error has occurred";
  if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url)){
    switch ($_GET["action"]) {
      case "getAmazonData":
          $value = getAmazonData();
          break;
      case "getGoogleImages":
          $value = getGoogleImages();
          break;
      case "getYouTubeVideos":
          $value = getYouTubeVideos();
          break;
     }   
  }

function getGoogleImages(){
        
    $base_url = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&rsz=8&imgsz=large&start=1";
    $search = "";
    
    if (isset($_GET["search"])){
      $search =  str_replace(' ', '%20', $_GET["search"]);
    }

    $url = $base_url . "&q=" . $search;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = json_decode(curl_exec($ch));
    //json_decode(
    curl_close($ch);
    return $response;

}


    

function getYouTubeVideos(){
        
    $base_url = "https://gdata.youtube.com/feeds/api/videos?";
    $search = "";
    
    if (isset($_GET["search"])){
      $search =  str_replace(' ', '+', $_GET["search"]);
    }

    $url = $base_url . "q=" . $search . "&orderby=relevance&format=6&start-index=1&max-results=50&v=2&alt=jsonc";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = json_decode(curl_exec($ch));
    //json_decode(
    curl_close($ch);
    return $response;

}


  function getAmazonData(){

      $search = "";

      if (isset($_GET["search"])){
         $search = $_GET["search"];
      }

      $url = amazon_get_signed_url($search);
      $response = file_get_contents($url);
      $parsed_xml = simplexml_load_string($response);

      foreach($parsed_xml->Items->Item as $current){

          $amazon_id = "";
          $title = "";
          $amazon_url = "";
          $genre = "";
          $image_url = "";
          $image_url = "";


          $actor = "";
          $artist = "";
          $audienceRating = "";
          $author = "";
          $category = "";
          $director = "";
          $language = "";
          $releaseDate = "";
          $label = "";
          $editorialreview = "";
          $product_group = "";


          if($current->ItemAttributes->ProductGroup == 'DVD' ||
              $current->ItemAttributes->ProductGroup == 'TV Series Episode Video on Demand'||
              $current->ItemAttributes->ProductGroup == 'Video'||
              $current->ItemAttributes->ProductGroup == 'Movie') {
              $amazon_id = implode((array)$current->ASIN);
              $title = implode((array)$current->ItemAttributes->Title);
              $amazon_url = implode((array)$current->DetailPageURL);
              $genre = implode((array)$current->ItemAttributes->Genre);

              $actor = implode((array)$current->ItemAttributes->Actor);
              $artist = implode((array)$current->ItemAttributes->Artist);
              $audienceRating = implode((array)$current->ItemAttributes->AudienceRating);
              $author = implode((array)$current->ItemAttributes->Author);
              $category = implode((array)$current->ItemAttributes->Category);
              $director = implode((array)$current->ItemAttributes->Director);
              $language = implode((array)$current->ItemAttributes->Language);
              $releaseDate = implode((array)$current->ItemAttributes->ReleaseDate);
              $label = implode((array)$current->ItemAttributes->Label);
              $editorialreview = implode((array)$current->EditorialReviews->EditorialReview->Content);
              $product_group = implode((array)$current->ItemAttributes->ProductGroup);


          }

          if(strlen($current->LargeImage->URL)>0) {
              $image_url = implode((array)$current->LargeImage->URL);
          }

          if(strlen($amazon_id)>0){

            $myArr[] = array("amazon_id" => $amazon_id,
                              "title" => $title,
                              //"amazon_url" =>$amazon_url,
                              "amazon_url" =>substr($amazon_url, 0, strrpos($amazon_url, "%3FSubscriptionId")),
                              "genre" => $genre,
                              "image_url" => $image_url,
                              "actor" => $actor,
                              "artist" => $artist,
                              "audiencerating" => $audienceRating,
                              "author" => $author,
                              "category" => $category,
                              "director" => $director,
                              "language" => $language,
                              "releasedate" => $releasedate,
                              "label" => $label,
                              "editorialreview" => $editorialreview,
                              "product_group" => $product_group);

          }
      }

      $results = array('results' => $myArr);
      return $results;
  }


  
    function amazon_get_signed_url($searchTerm) {

        $AWS_ACCESS_KEY_ID = "AKIAJKLUGGMZ7VT4PXWA";
        $AWS_SECRET_ACCESS_KEY = "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";
        $AMAZON_ASSOC_TAG = "deezelappscom-20";

        //$AWS_ACCESS_KEY_ID = "AKIAJKI6J7WE42DSSBIQ";
        //$AWS_SECRET_ACCESS_KEY = "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";
        //$AMAZON_ASSOC_TAG = "flixa-20";

        $base_url = "http://ecs.amazonaws.com/onca/xml";
        $params = array(
              'AWSAccessKeyId' => $AWS_ACCESS_KEY_ID,
              'AssociateTag' => $AMAZON_ASSOC_TAG,
              'Version' => "2010-11-01",
              'Operation' => "ItemLookup", //&ItemId=B00455IVB6",
              'Service' => "AWSECommerceService",
              'ResponseGroup' => "Images, ItemAttributes, EditorialReview",
              'Condition' => "All",
              'Operation' => "ItemSearch",
              'SearchIndex' => 'DVD', //BLENDED DVD - VIDEOChange search index if required, you can also accept it as a parameter for the current method like $searchTerm
              'Keywords' => $searchTerm);


        //'ItemPage'=>"1",
        //'ResponseGroup'=>"Images,ItemAttributes,EditorialReview",

        if(empty($params['AssociateTag'])) {
            unset($params['AssociateTag']);
        }

        // Add the Timestamp
        $params['Timestamp'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());

        // Sort the URL parameters
        $url_parts = array();

        foreach(array_keys($params) as $key) {
            $url_parts[] = $key . "=" . str_replace('%7E', '~', rawurlencode($params[$key]));
        }

        sort($url_parts);

        // Construct the string to sign
        $url_string = implode("&", $url_parts);
        $string_to_sign = "GET\necs.amazonaws.com\n/onca/xml\n" . $url_string;

        // Sign the request
        $signature = hash_hmac("sha256", $string_to_sign, $AWS_SECRET_ACCESS_KEY, TRUE);

        // Base64 encode the signature and make it URL safe
        $signature = urlencode(base64_encode($signature));

        //$url = $base_url . '?' . $url_string . "&Signature=" . $signature;
        $url = $base_url . '?' . $url_string . "&Signature=" . $signature;

        return ($url);
    }



//return JSON array
exit(json_encode($value));
        ?>