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
      $scope.segment_active = 0;
      $scope.segment_limit = 0;
      $scope.segment_retag = 0;
      $scope.sub_criteria_id = 0;
      $scope.filterByTable = "RM00101";

      $scope.object_types = [
          {id:1,name:"CUSTOMER"},
          {id:2,name:"PROSPECT"},
          {id:3,name:"PRODUCT"}
      ];

      $scope.tables = [
          {id:1,name:"RM00101"},
          {id:2,name:"HSSalesRecapHdr"}
      ];

      $scope.fields = [
          {id:1,table: "RM00101",name:"PRICELEVEL", data_type: "string"},
          {id:2,table: "RM00101",name:"CITY", data_type: "string"},
          {id:3,table: "RM00101",name:"STATE", data_type: "string"},
          {id:4,table: "RM00101",name:"ZIP", data_type: "string"},
          {id:5,table: "HSSalesRecapHdr",name:"DOCDATE", data_type: "date"},
          {id:6,table: "HSSalesRecapHdr",name:"SUBTOTAL", data_type: "int"}
      ];

      $scope.operators = [
          {id:1,name:"="},
          {id:2,name:"LIKE"},
          {id:3,name:"IN"},
          {id:4,name:">"},
          {id:5,name:"<"},
          {id:6,name:"<>"},
          {id:7,name:"NOT LIKE"}
      ];

      $scope.sub_criterias = [
          {
             id:-1,
             sub_group_name:"",
             table:"",
             field:"",
             operator:"",
             criteria:"",
             data_type:""
         }
       ];

       $scope.sub_criteria_groups = [
           {
              id:0,
              name:""
          }
        ];

      $scope.btnAddCiteria = function(_id, _sub_group_name, _table, _field, _operator, _criteria, _data_type){

        alert(_id);
        //alert(_sub_group_name);
        //alert(_table);
        //alert(_field);
        //alert(_operator);
        //alert(_criteria);
        //alert(_data_type);

        var criteria = {
           id:_id,
           sub_group_name:_sub_group_name,
           table:_table,
           field:_field,
           operator:_operator,
           criteria:_criteria,
           data_type:_data_type
       };

        //$scope.sub_criteria_id = $scope.sub_criteria_id + 1;
        $scope.sub_criterias.push(criteria);
      }

      $scope.btnRemoveCiteria = function(index){
        $scope.sub_criterias.splice(index,1);
      }

      $scope.init = function(){
          $scope.loaded = true;
      }

      $scope.btnSaveSegment = function(){

          $http.post("/db/add-segment.php", {
              segment_name: $scope.segment_name,
              segment_description: $scope.segment_description,
              segment_active: $scope.segment_active,
              segment_limit: $scope.segment_limit,
              segment_retag: $scope.segment_retag,
              segment_object_type_id: $scope.object_type_list.id
              })
                  .success(function (data, status, headers, config) {
                      alert("Segment Added");

                  }).error(function (data, status, headers, config) {
                      alert(status);
              });
      }

    }])
