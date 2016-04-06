<div>

    <div class="visible-xs" ng-include="'/flix/flix_main_movie_mobile.html'"></div>

    <div class="visible-lg visible-md visible-sm" ng-include="'/flix/flix_main_movie_desktop.html'"></div>

   <div ng-show="1">
      <?php require $_SERVER['DOCUMENT_ROOT'] . "/flix/flix_main_prospect_page.html" ?>
   </div>
   <div ng-show="1">
      <?php require $_SERVER['DOCUMENT_ROOT'] . "/flix/flix_main_welcome_message.html" ?>
   </div>
   <div ng-show="1">
      <?php require $_SERVER['DOCUMENT_ROOT'] . "/flix/flix_main_logo.html" ?>
   </div>
   <div ng-show="1">
      <?php require $_SERVER['DOCUMENT_ROOT'] . "/flix/flix_main_footer.html" ?>
   </div>
</div>