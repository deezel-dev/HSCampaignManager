<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requiresession.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/requireadminsession.php";
?>
<!DOCTYPE html>
<html data-ng-app="app">
<head>
    <title>FlixAcademy Admin Console</title>
    <!-- Bootstrap CSS -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container" style="width:100%;">
  <div class="navbar" data-ng-controller="searchCtrl">
    <div class="navbar-inner">
      <a class="brand" ui-sref="index">FlixAcademy</a>
      <ul class="nav">
        <!--<li><a ui-sref="index">Home</a></li>
        <li><a ui-sref="dataMaintenance">Data Maintenance</a></li>-->
        <li><input type="text" align="right" style="float:right;top-margin:10px" data-ng-model="search"></li>
        <li>&nbsp;&nbsp;&nbsp;<button ng-click="getData(search)">SEARCH</button></li>
      </ul>
    </div>
  </div>        
  
    <!--
  <div class="row">
    <div class="span6" style="width:58%;">
      <div class="well" ui-view="viewA"></div>
    </div>
    <div class="span6" style="width:37%;">
      <div class="well" ui-view="viewB"></div>
    </div>
  </div> 

 <div ui-view="viewB"></div>
    <br>
    <div ui-view="viewA"></div>

-->
    

    <div class="row">
    <div class="span6" style="width:58%;">
      <div class="well" ui-view="viewA"></div>
    </div>
    <div class="span6" style="width:37%;" ng-hide="$state.current.name === 'home'">
        
        <!-- "$state.current.name === 'home'"  OR "$state.includes('flix')" -->
        
      <div class="well" ui-view="viewB"></div>
    </div>
  </div>


  <!-- Angular -->
  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.4/angular.min.js"></script>
   <!-- UI-Router -->
  <script src="//angular-ui.github.io/ui-router/release/angular-ui-router.js"></script>
  <script src="app.js"></script>
  <script src="suggestionCtrl.js"></script>
  <script src="dataMaintenanceCtrl.js"></script>
    <script src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("search", "1");
      </script>
    
    
    
    <script src="https://apis.google.com/js/client.js?onload=googleApiClientReady"></script>
    
     <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    
  <!-- App Script -->
  <script>
  </script>
</body>
</html>
