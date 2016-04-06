<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/create.php";
header("Content-Type: text/json");

$body = json_decode(file_get_contents("php://input"));

$result = createLocalAccount($body->email, $body->displayName, $body->password, $body->confirmPassword, $body->isTermsAccepted);
echo(json_encode($result));
?>