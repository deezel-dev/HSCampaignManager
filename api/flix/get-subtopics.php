<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/flix/search.php";
header("Content-Type: text/json");

$request = json_decode(file_get_contents("php://input"));

$value = getChildSubjects($request->{"selectedCategory"}, true);
echo(json_encode($value));
?>