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
        .state('campaign_manager', {
            url: '/campaign_manager',
            templateUrl: '/site/campaign_manager.html',
            controller: 'campaignCntrl'
        })

        // INDEX STATES AND NESTED VIEWS ========================================
        .state('segment_manager', {
            url: '/segment_manager',
            templateUrl: '/site/segment_manager.html'
        })

    } ])
    .controller("indexCtrl", ['$scope', '$window', function ($scope, $window) {

        $scope.isUser = false;
        $scope.profileId = -1;
        $scope.rootUrl = $window.location.protocol + "//" + $window.location.host;
        //dataService.rootPath = $scope.rootUrl;
        $scope.showSuggestion = false;


        $scope.setProfileData = function (profileId) {

            var isUser = false;

            if (profileId > 0) {
                isUser = true;
            }

            $scope.isUser = isUser;
            $scope.profileId = profileId;

            //dataService.isUser = isUser;
            //dataService.setProfileId(profileId);
        }



    } ])
    .controller("campaignCntrl", ['$scope', '$http', function ($scope, $http) {

        $scope.loaded = false;
        $scope.campaign_name = "";
        $scope.campaign_description = "";
        $scope.campaign_manager = "";
        $scope.start_date = "";
        $scope.end_date = "";
        $scope.output_channel = "";
        $scope.selected_segment = "";
        $scope.selectedSegment="";
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
        