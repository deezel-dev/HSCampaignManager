<?php
$GLOBALS["IsDevelopment"] = ($_SERVER['HTTP_HOST'] == "flixacademy.cloudapp.net") || ($_SERVER['HTTP_HOST'] == "flixacademy-dev-danny.azurewebsites.net") ? 1 : 0;

if ($GLOBALS["IsDevelopment"] == 1) {
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED);
}

function displayErrorIfDevelopment() {
    if ($GLOBALS["IsDevelopment"] == 1) {
        ?>
        window.alert("ERROR:\n\n" + data);
        <?php
    }
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/include/constants.php";
session_start();
?>