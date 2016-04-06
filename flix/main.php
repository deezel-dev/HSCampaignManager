<div ng-controller="mainCtrl">
   <!-- isSearching
      <div ng-hide="1" style="z-index: 2550; position: fixed; margin: 0; padding: 0; top: 0; left: 0; bottom: 0; right: 0; background: rgba(0, 0, 0, 0.7); text-align: center;">
         <span style="display: inline-block; height: 100%; vertical-align: middle;"></span>
         <img src="/public/images/ajax-loader.gif" alt="Loading..." />
      </div>  -->
   <div ng-show="showSearchbar">
      <!--<?php //require $_SERVER['DOCUMENT_ROOT'] . "/flix/flix_main_searchbar.html" ?> -->
   </div>

  
    <div ng-show="1">
        <?php //require $_SERVER['DOCUMENT_ROOT'] . "/flix/flix_main_gridview.html" ?>
    </div>

   <div>
      <?php require $_SERVER['DOCUMENT_ROOT'] . "/flix/flix_main_prospect_page.html" ?>
   </div>

   <div ng-show="1">
      <?php //require $_SERVER['DOCUMENT_ROOT'] . "/flix/flix_main_welcome_message.html" ?>
   </div>
   <div ng-show="1">
      <?php //require $_SERVER['DOCUMENT_ROOT'] . "/flix/flix_main_logo.html" ?>
   </div>
   <div ng-show="1">
      <?php //require $_SERVER['DOCUMENT_ROOT'] . "/flix/flix_main_footer.html" ?>
   </div>
</div>