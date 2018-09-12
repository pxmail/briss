define(['app'], function (app) {
    'use strict';

    app.config(['$stateProvider', '$urlRouterProvider',
        function ($stateProvider, $urlRouterProvider) {

            $stateProvider.state('trainee', {
                templateUrl: 'page/trainee/index.html',
                controller: 'traineeCtrl',
                abstract: true
            });

            $stateProvider.state('trainee.home', {
                url: '/home',
                templateUrl: 'page/trainee/home.html',
                controller: 'traineeHomeCtrl',
                cache: false
            });

            $stateProvider.state('trainee.evaluation', {
                url: '/evaluation',
                templateUrl: 'page/trainee/evaluation.html',
                controller: 'traineeEvaluationCtrl'
            });

            $stateProvider.state('trainee.history', {
                url: '/history/:date',
                templateUrl: 'page/trainee/history.html',
                controller: 'traineeHistoryCtrl',
                abstract: true
            });
            
            $stateProvider.state('trainee.history.data', {
                url: '/data',
                templateUrl: 'page/trainee/history.data.html'
            });
            
            $stateProvider.state('trainee.history.evaluation', {
                url: '/evaluation',
                templateUrl: 'page/trainee/history.evaluation.html'
            });

            $stateProvider.state('login', {
                url: '/login',
                templateUrl: 'page/trainee/login.html',
                controller: 'traineeLoginCtrl'
            });

            $stateProvider.state('register', {
                url: '/register',
                templateUrl: 'page/trainee/register.html',
                controller: 'traineeRegisterCtrl'
            });
            
            $stateProvider.state('sports_event', {
                url: '/sports',
                templateUrl: 'page/inc/sports.html',
                controller: 'sportEventCtrl'
            });

            $stateProvider.state('trainee.menu', {
                url: '/menu',
                templateUrl: 'page/trainee/menu.html'
            });

            $stateProvider.state('trainee.profile', {
                url: '/profile',
                templateUrl: 'page/trainee/profile.html',
                controller: 'traineeProfileCtrl'
            });
            
            $stateProvider.state('trainee.sports_event', {
                url: '/sports',
                templateUrl: 'page/inc/sports.html',
                controller: 'sportEventCtrl'
            });

            $stateProvider.state('trainee.password', {
                url: '/password',
                templateUrl: 'page/trainee/password.html',
                controller: 'traineePasswordCtrl'
            });

            $stateProvider.state('trainee.calendar', {
                url: '/calendar',
                templateUrl: 'page/trainee/calendar.html',
                controller: 'traineeCalendarCtrl'
            });
            
            $stateProvider.state('trainee.data', {
                url: '/data',
                templateUrl: 'page/trainee/data.html',
                abstract: true
            });
            
            $stateProvider.state('trainee.data.movements', {
                url: '/',
                templateUrl: 'page/trainee/movements.html',
                controller: 'traineeMovementsCtrl'
            });
            
            $stateProvider.state('trainee.data.report', {
                url: '/:movement_id',
                templateUrl: 'page/coach/report.html',
                controller: 'traineeReportCtrl'
            });
            
            $stateProvider.state('trainee.end_train', {
                url: '/end_train',
                templateUrl: 'page/trainee/evaluation.html',
                controller: 'traineeEndTrainCtrl'
            });

            $urlRouterProvider.otherwise('/home');
        }]);


});
