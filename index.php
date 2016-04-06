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
       <link href="/public/css/flix_remix.css" rel="stylesheet">
   </head>
   <body data-ng-controller="indexCtrl" ng-init="setProfileData(<?php echo($profileID) ?>)">        
     <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.body-navbar.php" ?>
     <form class="visible-md-block visible-lg-block visible-xs-block visible-sm-block" ui-view autoscroll="true"></form>
      <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.body-scripts.php" ?>
      <script src="/site/app.js"></script>
   </body>
</html>
