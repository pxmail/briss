define(['app'], function (app) {
    'use strict';

    app.config(['$stateProvider', '$urlRouterProvider',
        function ($stateProvider, $urlRouterProvider) {

            $stateProvider.state('home', {
                url: '/home',
                templateUrl: 'page/touch/home.html',
                controller: 'touchHomeCtrl',
                cache: false
            });

            $stateProvider.state('login', {
                url: '/login',
                templateUrl: 'page/touch/login.html',
                controller: 'touchLoginCtrl'
            });

            $stateProvider.state('query', {
                url: '/query',
                templateUrl: 'page/touch/query.html',
                controller: 'traineeQueryCtrl'
            });

            $stateProvider.state('trainee', {
                url: '/trainee/:trainee_id',
                templateUrl: 'page/touch/trainee.html',
                controller: 'touchTraineeCtrl',
                cache: false,
                abstract: true
            });

            $stateProvider.state('trainee.home', {
                url: '/',
                templateUrl: 'page/touch/trainee_home.html',
                controller: 'touchTraineeHomeCtrl'
            });

            $stateProvider.state('trainee.calendar', {
                url: '/calendar',
                templateUrl: 'page/touch/trainee_calendar.html',
                controller: 'traineeCalendarCtrl'
            });
            
            $stateProvider.state('trainee.history', {
                url: '/history/:date',
                templateUrl: 'page/touch/trainee_home.html',
                controller: 'traineeHistoryCtrl'
            });
            
            $stateProvider.state('trainee.data', {
                url: '/report',
                abstract: true,
                templateUrl: 'page/touch/data.html'
            });
            
            $stateProvider.state('trainee.data.movements', {
                url: '/',
                templateUrl: 'page/touch/movements.html',
                controller: 'traineeMovementsCtrl'
            });
            
            $stateProvider.state('trainee.data.report', {
                url: '/:movement_id',
                templateUrl: 'page/touch/report.html',
                controller: 'traineeReportCtrl'
            });
            
            $stateProvider.state('trainee.profile', {
                url: '/profile',
                templateUrl: 'page/touch/trainee_profile.html',
                controller: 'traineeProfileCtrl'
            });
            
            $urlRouterProvider.otherwise('/home');
        }]);


});
