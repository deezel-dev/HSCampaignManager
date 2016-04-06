<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requireadminsession.php";
?>
<!DOCTYPE html>
<html ng-app="app">
<head>    
    <!-- CSS (load bootstrap) -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    
    <!-- start: CSS -->
	<link id="bootstrap-style" href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
	<link id="base-style" href="css/style.css" rel="stylesheet">
	<link id="base-style-responsive" href="css/style-responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/simple.css" rel="stylesheet">
   
    <!-- JS (load angular, ui-router, and our custom js file) -->
    <script src="http://code.angularjs.org/1.2.13/angular.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.2.8/angular-ui-router.min.js"></script>
    <script src="app.js"></script>
    <script src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("search", "1");
      </script>
    <script src="https://apis.google.com/js/client.js?onload=googleApiClientReady"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="https://apis.google.com/js/client.js?onload=init"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
    <script src="angular-youtube-embed.js"></script>

    <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/admin.head.php" ?>
    
</head>
<body>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/admin.body-navbar.php" ?>
    <form class="visible-xs-block visible-sm-block" id="wrapper" style="margin: -21px;padding: 5px" ui-view></form>
    <form class="visible-md-block visible-lg-block" id="wrapper" style="margin-top: -21px;padding: 10px" ui-view></form>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.body-scripts.php" ?>
</body>
</html>