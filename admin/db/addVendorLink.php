<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requireadminsession.php";
header("Content-Type: text/json");

$link = json_decode(file_get_contents("php://input"));

$r2 = addVendor($link->{"product_id"},
               $link->{"vendor_id"},
               $link->{"vendor_product_link"},
               $link->{"vendor_price"},
               $link->{"vendor_isFree"},
               $link->{"vendor_isFree"},
               $link->{"use_search"});


function addVendor($product_id, $vendor_id, $vendor_product_link, $vendor_price, $vendor_isFree, $use_search) {
    
    
    $myArr = null;    
 
    
    $sql = "INSERT INTO product_vendors(product_id,vendor_id,vendor_product_link,vendor_price,vendor_isFree, use_search)
            VALUES (" . $product_id .", 
                    " . $vendor_id .",
                    '" . $vendor_product_link ."',
                    " . $vendor_price .",
                    " . $vendor_isFree .",
                    " . $use_search .")";
      
    $data = getDatabase();
    
    if ($data->open()) {
        if($data->insertData($sql)){
            echo "insert - ok";
            $myArr = array(
                "status"  =>"sucess");
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
    
    return array('product_vendorStatus' => $myArr);
}


function padSql($subject){
    return str_replace ("'","''",$subject);
  }


?>