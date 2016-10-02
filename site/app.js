var app = angular.module('app', ['ui.router', 'ui.bootstrap'])
    .config(['$urlRouterProvider', '$stateProvider', function ($urlRouterProvider, $stateProvider) {

        $urlRouterProvider.otherwise('/main');

        $stateProvider

        // INDEX STATES AND NESTED VIEWS ========================================
            .state('main', {
                url: '/main',
                templateUrl: '/site/main.php',
                params: {
                    mode: 1
                }
            })

            // INDEX STATES AND NESTED VIEWS ========================================
            .state('segment_manager', {
                url: '/segment_manager',
                templateUrl: '/site/segment_manager.html',
                controller: 'segmentCntrl'
            })

        // INDEX STATES AND NESTED VIEWS ========================================
        .state('campaign_manager', {
            url: '/campaign_manager',
            templateUrl: '/site/campaign_manager.html',
            controller: 'campaignCntrl'
        })

    }])
    .controller("indexCtrl", ['$scope', '$window', function ($scope, $window) {

        $scope.isUser = false;
        $scope.profileId = -1;
        $scope.rootUrl = $window.location.protocol + "//" + $window.location.host;
        $scope.showSuggestion = false;

        $scope.setProfileData = function (profileId) {

            var isUser = false;

            if (profileId > 0) {
                isUser = true;
            }

            $scope.isUser = isUser;
            $scope.profileId = profileId;

        }

    }])
    .controller("campaignCntrl", ['$scope', '$http', function ($scope, $http) {

        $scope.loaded = false;
        $scope.campaign_name = "";
        $scope.campaign_description = "";
        $scope.campaign_manager = "";
        $scope.start_date = "";
        $scope.end_date = "";
        $scope.output_channel = "";
        //$scope.selected_segment = {};
        $scope.selectedSegment={};
        $scope.channel = {};
        $scope.selected_segments = [];

        $scope.channels = [
            {id:1,name:"EMAIL"},
            {id:2,name:"OUTBOUND CALL"},
            {id:3,name:"MAIL PIECE"},
            {id:4,name:"ORDER INSERT"},
            {id:5,name:"CATALOG"},
            {id:6,name:"SOCIAL MEDIA"},
            {id:7,name:"ROAD REPS"}
        ];

        $scope.segments = [
            {id:1,name:"PROFGROWER"},
            {id:2,name:"HOMEGARDEN"},
            {id:3,name:"SOUTHERN GROWER"},
            {id:4,name:"NEW CUSTOMER"},
            {id:5,name:"REACTIVATE"},
            {id:6,name:"SLEEPER"},
            {id:7,name:"LOSS"}
        ];

        $scope.init = function(){
            $scope.loaded = true;
        }

        $scope.outputChannelSelected = function (){
        }

        $scope.segmentSelected = function (){
            $scope.selected_segments.push({id:0,name:$scope.selectedSegment});
        }

        $scope.removeSegment = function (segment){
            var index = $scope.selected_segments.indexOf(segment);
            $scope.selected_segments.splice(index, 1);
        }

        $scope.btnSaveCampaign = function(){

            $http.post("/db/add-campaign.php", {
                campaign_name: $scope.campaign_name,
                campaign_description: $scope.campaign_description,
                campaign_manager: $scope.campaign_manager,
                start_date: $scope.start_date,
                end_date: $scope.end_date
                })
                    .success(function (data, status, headers, config) {
                        alert("Campaign Added");

                    }).error(function (data, status, headers, config) {
                        alert(status);
                });

        }

    } ])
    .controller("segmentCntrl", ['$scope', '$http', function ($scope, $http) {

      $scope.loaded = false;
      $scope.segment_name = "";
      $scope.segment_description = "";
      $scope.segment_active = false;
      $scope.segment_retag = false;
      $scope.segment_limit = 0;
      $scope.segment_object_type_id =  {};
      $scope.object_type = {};

      $scope.object_types = [
          {id:1,name:"CUSTOMER"},
          {id:2,name:"PROSPECT"},
          {id:3,name:"PRODUCT"}
      ];

      $scope.init = function(){
          $scope.loaded = true;
      }

      $scope.objectTypeSelected = function (){
        alert("scope.segment_object_type_id: " + $scope.segment_object_type_id);
      }

      $scope.btnSaveSegment = function(){

        alert("scope.segment_name: " + $scope.segment_name);
        alert("scope.segment_description: " + $scope.segment_description);
        alert("scope.segment_active: " + $scope.segment_active);
        alert("scope.segment_limit: " + $scope.segment_limit);
        alert("scope.segment_retag: " + $scope.segment_retag);
        alert("scope.segment_retag: " + $scope.segment_retag);
        alert("scope.segment_object_type_id: " + $scope.segment_object_type_id.id);


          $http.post("/db/add-segment.php", {
              segment_name: $scope.segment_name,
              segment_description: $scope.segment_description,
              segment_active: $scope.segment_active,
              segment_limit: $scope.segment_limit,
              segment_retag: $scope.segment_retag,
              segment_object_type_id: $scope.segment_object_type_id.id
              })
                  .success(function (data, status, headers, config) {
                      alert("Segment Added");

                  }).error(function (data, status, headers, config) {
                      alert(status);
              });

      }

    } ])
