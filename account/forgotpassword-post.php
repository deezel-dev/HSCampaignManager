<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/manage.php";
header("Content-Type: text/json");

$body = json_decode(file_get_contents("php://input"));

$result = updatePasswordWithToken($body->email, $body->key, $body->password, $body->confirmPassword);
echo(json_encode($result));
?>