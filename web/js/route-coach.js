
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
                templateUrl: 'page/coach/login.html',
                controller: 'coachLoginCtrl'
            });

            $stateProvider.state('query', {
                url: '/query',
                templateUrl: 'page/touch/query.html',
                controller: 'traineeQueryCtrl'
            });

            $stateProvider.state('settings', {
                url: '/settings',
                templateUrl: 'page/coach/settings.html',
                controller: 'settingsCtrl',
                abstract: true
            });
            
            $stateProvider.state('settings.trainees', {
                url: '/trainees',
                templateUrl: 'page/coach/trainee_list.html',
                controller: 'traineeListCtrl',
                reload: true
            });
            
            $stateProvider.state('settings.trainee_select', {
                url: '/trainee_select',
                templateUrl: 'page/coach/trainee_select.html',
                controller: 'traineeSelectCtrl'
            });
            
            $stateProvider.state('settings.new_trainee', {
                url: '/new_trainee',
                templateUrl: 'page/coach/trainee_new.html',
                controller: 'traineeNewCtrl'
            });
            
            $stateProvider.state('settings.sports_event', {
                url: '/sports',
                templateUrl: 'page/inc/sports.html',
                controller: 'sportEventCtrl'
            });
            
            $stateProvider.state('settings.new_coach', {
                url: '/new_coach',
                templateUrl: 'page/coach/coach_new.html',
                controller: 'coachManagementCtrl'
            });
            
            $stateProvider.state('settings.coaches', {
                url: '/coaches',
                templateUrl: 'page/coach/coach_list.html',
                controller: 'coachManagementCtrl'
            });
            
            $stateProvider.state('settings.coach_profile', {
                url: '/coach/:coach_id',
                templateUrl: 'page/coach/coach_profile.html',
                controller: 'coachProfileCtrl'
            });
            
            $stateProvider.state('settings.touch_code', {
                url: '/touch_code',
                templateUrl: 'page/coach/touch_code.html',
                controller: 'coachManagementCtrl'
            });
            
            $stateProvider.state('settings.base', {
                url: '/base',
                templateUrl: 'page/coach/base.html',
                controller: 'baseCtrl',
                cache: false
            });
            
            $stateProvider.state('settings.new_base', {
                url: '/new_base',
                templateUrl: 'page/coach/edit_base.html',
                controller: 'baseCtrl'
            });
            
            $stateProvider.state('settings.edit_base', {
                url: '/edit_base/:base_id',
                templateUrl: 'page/coach/edit_base.html',
                controller: 'baseCtrl',
                cache: false
            });
            
            $stateProvider.state('settings.password', {
                url: '/password',
                templateUrl: 'page/coach/password.html',
                controller: 'coachPasswordCtrl'
            });
            
            $stateProvider.state('settings.trainee_profile', {
                url: '/trainee/:trainee_id',
                templateUrl: 'page/touch/trainee_profile.html',
                controller: 'traineeProfileCtrl'
            });
            
            $stateProvider.state('settings.edit_trainee', {
                url: '/edit_trainee/:trainee_id',
                templateUrl: 'page/coach/trainee_profile_edit.html',
                controller: 'traineeProfileCtrl'
            });

            $stateProvider.state('trainee', {
                url: '/trainee/:trainee_id',
                templateUrl: 'page/coach/trainee.html',
                controller: 'coachTraineeCtrl',
                cache: false,
                abstract: true
            });         

            $stateProvider.state('trainee.home', {
                url: '/',
                templateUrl: 'page/coach/trainee_home.html',
                controller: 'coachTraineeHomeCtrl'
            });

            $stateProvider.state('trainee.calendar', {
                url: '/calendar',
                templateUrl: 'page/touch/trainee_calendar.html',
                controller: 'traineeCalendarCtrl'
            });
            
            $stateProvider.state('trainee.history', {
                url: '/history/:date',
                templateUrl: 'page/coach/trainee_history.html',
                controller: 'traineeHistoryCtrl'
            });
            
            $stateProvider.state('trainee.data', {
                url: '/report',
                abstract: true,
                templateUrl: 'page/touch/data.html'
//                controller: 'traineeDataCtrl'
            });
            
            $stateProvider.state('trainee.data.movements', {
                url: '/',
                templateUrl: 'page/touch/movements.html',
                controller: 'traineeMovementsCtrl'
            });
            
            $stateProvider.state('trainee.data.report', {
                url: '/:movement_id',
                templateUrl: 'page/coach/report.html',
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