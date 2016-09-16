var app = angular.module('app', ['ui.router', 'ui.bootstrap'])
    .config(['$urlRouterProvider', '$stateProvider', function ($urlRouterProvider, $stateProvider) {

        $urlRouterProvider.otherwise('/main');

        $stateProvider

        // INDEX STATES AND NESTED VIEWS ========================================
        .state('campaign_manager', {
            url: '/campaign_manager',
            templateUrl: '/site/campaign_manager.html'
        })

        // INDEX STATES AND NESTED VIEWS ========================================
        .state('segment_manager', {
            url: '/segment_manager',
            templateUrl: '/site/segment_manager.html'
        })

    } ])