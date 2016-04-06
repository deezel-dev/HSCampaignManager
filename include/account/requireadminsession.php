<?php
if (!isset($_SESSION[SESSION_USER_ISADMINISTRATOR]) || !$_SESSION[SESSION_USER_ISADMINISTRATOR]) {
    header("Location: http://" . $_SERVER['HTTP_HOST'] . "/index.php");
    die();
}
?>