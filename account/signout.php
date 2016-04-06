<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
unset($_SESSION[SESSION_PROFILE_DISPLAYNAME]);
unset($_SESSION[SESSION_LOCAL_ID]);
unset($_SESSION[SESSION_PROFILE_ID]);
header("Location: http://" . $_SERVER['HTTP_HOST'] . "/index.php");
die();
?>