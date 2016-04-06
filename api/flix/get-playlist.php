<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";

header("Content-Type: text/json");

$value = getPlaylist();

echo(json_encode($value));

function getPlaylist() {

    $profile_id = -1;

    if (isset($_GET["profile_id"])){
        $profile_id = $_GET["profile_id"];
    }

    if($profile_id > 0){

    $sql = "SELECT DISTINCT
        product.product_id,
        user_playlist.profile_id
        FROM user_playlist INNER JOIN product ON user_playlist.product_id = product.product_id
        WHERE user_playlist.profile_id = " . $profile_id;

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
          if ($row['product_id'] <> '') {
            $product_id = intval($row['product_id']);
            $product_ids[] = array("product_id" => $product_id);
          }
        }
       }
    }
    }

    $results = array('product_ids' => $product_ids);

    return $results;

  }


?>