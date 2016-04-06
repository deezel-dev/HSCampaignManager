<?php
if (!isset($_SESSION[SESSION_PROFILE_ID])) {
    header("Location: http://" . $_SERVER['HTTP_HOST'] . "/account/signin.php?follow=" . urlencode($_SERVER["PHP_SELF"]));
    die();
}
//else if ($_SESSION[SESSION_PROFILE_ISEMAILVERIFIED] == false && $_SERVER['PHP_SELF'] != "/account/verifyemail.php") {
//    header("Location: http://" . $_SERVER['HTTP_HOST'] . "/account/verifyemail.php");
//    die();
//}
?>