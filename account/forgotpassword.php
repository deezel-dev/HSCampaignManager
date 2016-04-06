<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/manage.php";

$email = $_GET["email"];
$result = sendPasswordResetEmail($email);
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
                    <h3>Help is on its way</h3>
                    <p>We've sent you a link to reset your password; please check your email as it should be arriving shortly.</p>
                    <?php } else { ?>
                    <h3>We tried to send you an email; but an error occurred on our end.</h3>
                    <p>If this problem persists, please contact us at <a href="mailto:staff@flixacademy.com">staff@flixacademy.com</a> and we will assist you.</p>
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