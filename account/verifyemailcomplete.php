<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/create.php";

if ($_SESSION["IsEmailVerified"] == true) {
    header("Location: http://" . $_SERVER['HTTP_HOST'] . "/account/index.php");
    die();
}
else if (!isset($_GET["key"])) {
    session_unset();
    header("Location: http://" . $_SERVER['HTTP_HOST'] . "/index.php");
    die();
}

$email = $_GET["email"];
$token = $_GET["key"];
$result = verifyEmailWithToken($email, $token);
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.head.php" ?>
    </head>
    <body ng-app="flixAcademy">
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.body-navbar.php" ?>
        
        <div class="container">
            <form ng-controller="SignInController" ng-show="isLoaded">
                <div class="row visible-md-block visible-lg-block" id="logoRow" ng-hide="movies.length > 0">
                    <?php if ($result) { ?>
                        <h3>Thank you</h3>
                        <p>Please feel free to <a href="/account/index.php">review your profile details</a> or <a href="/index.php">search for Flix</a>.</p>
                    <?php } else { ?>
                        <h3>We were unable to verify your email address</h3>
                        <p>We would be glad to help if you reach out to us at <a href="mailto:staff@flixacademy.com">staff@flixacademy.com</a>.</p>
                    <?php } ?>
                </div>
                <div class="row">
                    <p style="text-align: center; font-size: 10pt;">Copyright &copy; 2015 FlixAcademy, Inc. All rights reserved.</p>
                </div>
            </form>
        </div>
        
        
        
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.body-scripts.php" ?>
    </body>
</html>