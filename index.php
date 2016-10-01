<?php
   require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";

   $profileID = -1;
   $isUser = FALSE;
   $rootPath = $_SERVER['DOCUMENT_ROOT'];

   if (isset($_SESSION[SESSION_PROFILE_ID])) {
       $profileID = $_SESSION[SESSION_PROFILE_ID];
       $isUser = TRUE;
   }

   ?>

<!DOCTYPE html>
<html data-ng-app="app" lang="en">
   <head>
      <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.head.php" ?>
   </head>
   <body data-ng-controller="indexCtrl" ng-init="setProfileData(<?php echo($profileID) ?>)">
     <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.body-navbar.php" ?>
     <form class="visible-lg-block visible-md-block visible-sm-block visible-xs-block" style="padding:15px;" ui-view autoscroll="true"></form>
      <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.body-scripts.php" ?>
      <script src="/site/app.js"></script>
   </body>
</html>
