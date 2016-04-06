<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/create.php";

if ($_SESSION[SESSION_PROFILE_ISEMAILVERIFIED] == true) {
    header("Location: http://" . $_SERVER['HTTP_HOST'] . "/account/index.php");
    die();
}

$result = sendVerificationEmail($_SESSION[SESSION_PROFILE_ID]);
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
                    <h3>We need to verify your email</h3>
                    <p>Check your inbox for a verification message with further instructions.</p>
                    <?php } else { ?>
                    <h3>We need to verify your email address; but an error occurred on our end while trying to send you an email.</h3>
                    <?php } ?>
                </div>
                <div class="row">
                    <p style="text-align: center; font-size: 10pt;">Copyright &copy; 2015 FlixAcademy, Inc. All rights reserved. <a href="/terms.php">Terms of Use</a></p>
                </div>
            </form>
        </div>
        
        
        
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.body-scripts.php" ?>
    </body>
</html>