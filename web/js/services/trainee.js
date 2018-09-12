define(['../modules', 'jquery'], function (services) {
    'use strict';

    var serviceObject = function ($resource, $state, $ionicHistory, $rootScope) {

        function listActive(callback) {
            var param;
            if ($rootScope.__terminal === 'coach' && localStorage.getItem('settings.myTraineeOnly') === 'true') {
                param = {
                    'list_mine': true
                };
            }
            $resource('api/trainee/list_active').get(param).$promise.then(function (resp) {
                if (resp.trainees)
                    callback(resp.trainees);
            });
        }

        function listTrainee(callback) {
            $resource('api/trainee/list_trainee').get().$promise.then(function (resp) {
                if (resp.amount)
                    callback(resp.amount);
            });
        }

        function query(keyword, callback) {
            var param = {keyword: keyword};
            if ($rootScope.__terminal === 'coach' && localStorage.getItem('settings.myTraineeOnly') === 'true') {
                param.list_mine = true;
            }

            $resource('api/trainee/query').get(param).$promise.then(function (resp) {
                if (resp.trainees)
                    callback(resp.trainees);
            });
        }

        function get(trainee_id, callback) {
            $resource('api/trainee/get').get({id: trainee_id}).$promise.then(callback);
        }


        function register(form, callback) {
            $resource('api/trainee/register').save(form).$promise.then(callback);
        }

        function login(form) {
            $resource('api/trainee/login').save(form).$promise.then(function (resp) {

                if (resp.access_token) {
                    $ionicHistory.nextViewOptions({
                        disableBack: true
                    });
                    localStorage.access_token = resp.access_token;
                    $state.go('trainee.home');
                }
            });
        }

        function stats(date, callback) {
            $resource('api/trainee/stats').get({date: date}).$promise.then(callback);
        }

        function isActive(callback) {
            $resource('api/trainee/is_active').get().$promise.then(callback);
        }

        function update(form, callback) {
            $resource('api/trainee/update').save(form).$promise.then(callback);
        }

        function updatePassword(form, callback) {
            $resource('api/trainee/update_password').save(form).$promise.then(callback);
        }

        function listAll(callback) {
            $resource('api/trainee/list_all').get().$promise.then(callback);
        }

        function traineeAudit(param, callback) {
            $resource('api/trainee/update_status').save(param).$promise.then(callback);
        }

        function listRecent(callback) {
            var param;
            if ($rootScope.__terminal === 'coach' && localStorage.getItem('settings.myTraineeOnly') === 'true') {
                param = {
                    'list_mine': true
                };
            }
            $resource('api/trainee/list_recent').get(param).$promise.then(callback);
        }

        function listPending(callback) {
            $resource('api/trainee/list_pending').get().$promise.then(callback);
        }

        return {
            listActive: listActive,
            listTrainee: listTrainee,
            query: query,
            get: get,
            register: register,
            login: login,
            isActive: isActive,
            update: update,
            updatePassword: updatePassword,
            listAll: listAll,
            traineeAudit: traineeAudit,
            listRecent: listRecent,
            listPending: listPending,
            trainee: {},
            stats: stats
        };
    };

    services.factory('traineeService', serviceObject);
});
