<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/flix/search.php";
header("Content-Type: text/json");

$search = json_decode(file_get_contents("php://input"));

$value = search($search->{"selectedCategory"}, null, null, $search->{"minimumLength"}, $search->{"maximumLength"}, $search->{"minimumAge"}, null, null);
echo(json_encode($value));
?>