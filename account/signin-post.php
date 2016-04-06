<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/signin.php";
header("Content-Type: text/json");

$body = json_decode(file_get_contents("php://input"));

$result = signInLocal($body->email, $body->password);
echo(json_encode($result));
?>