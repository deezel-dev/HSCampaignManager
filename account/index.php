<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";

// TEMPORARILY REDIRECT TO HOME UNTIL WE CREATE ACCOUNT MANAGEMENT PAGE
header("Location: http://" . $_SERVER['HTTP_HOST'] . "/index.php");
die();
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
                    <h3>Welcome!</h3>
                </div>
                <div class="row">
                    <p style="text-align: center; font-size: 10pt;">Copyright &copy; 2015 FlixAcademy, Inc. All rights reserved. <a href="/terms.php">Terms of Use</a></p>
                </div>
            </form>
        </div>
        
        
        
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.body-scripts.php" ?>
    </body>
</html>