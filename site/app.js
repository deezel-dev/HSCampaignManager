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
    .service('dataService', function ($http, $rootScope, $window, $q, $state) {
        var dataService = this;
    })
    .controller("indexCtrl", ['$scope', '$window', 'dataService', function ($scope, $window, dataService) {

        $scope.isUser = false;
        $scope.profileId = -1;
        $scope.rootUrl = $window.location.protocol + "//" + $window.location.host;
        dataService.rootPath = $scope.rootUrl;
        $scope.showSuggestion = false;


        $scope.setProfileData = function (profileId) {

            var isUser = false;

            if (profileId > 0) {
                isUser = true;
            }

            $scope.isUser = isUser;
            $scope.profileId = profileId;

            dataService.isUser = isUser;
            dataService.setProfileId(profileId);
        }



    } ])
    .controller("campaignCntrl", ['$scope', '$http.', function ($scope, $http) {

        $scope.campaign_name = "";
        $scope.campaign_description = "";
        $scope.campaign_manager = "";
        $scope.start_date = "";
        $scope.end_date = "";
        
        $scope.btnSaveCampaign = function(){

            alert("Saving campaign " + $scope.campaign_name);

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
        