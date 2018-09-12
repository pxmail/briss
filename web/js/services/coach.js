define(['../modules'], function (services) {
    'use strict';

    var serviceObject = function ($resource, $state, $ionicHistory) {
        function login(form) {
            $resource('api/coach/login').save(form).$promise.then(function (resp) {
                if (resp.access_token) {
                    $ionicHistory.nextViewOptions({
                        disableBack: true
                    });
                    localStorage.access_token = resp.access_token;
                    $state.go('home');
                }
            });
        }

        function updatePassword(form, callback) {
            $resource('api/coach/update_password').save(form).$promise.then(callback);
        }

        function create(param, callback) {
            $resource('api/coach/create').save(param).$promise.then(callback);
        }

        function listAll(callback) {
            $resource('api/coach/lists').get().$promise.then(callback);
        }

        function listBase(callback) {
            $resource('api/base/lists').get().$promise.then(callback);
        }

        function createBase(param, callback) {
            $resource('api/base/create').save(param).$promise.then(callback);
        }

        function deleteBase(param, callback) {
            $resource('api/base/delete').save(param).$promise.then(callback);
        }

        function updateBase(param, callback) {
            $resource('api/base/update').save(param).$promise.then(callback);
        }

        function genCode(callback) {
            $resource('api/coach/gen_touch_code').get().$promise.then(callback);
        }

        function resetTraineePassword(param, callback) {
            $resource('api/coach/reset_trainee_password').save(param).$promise.then(callback);
        }

        function get(param, callback) {
            $resource('api/coach/get').get(param).$promise.then(callback);
        }

        function resetPassword(param, callback) {
            $resource('api/coach/reset_password').save(param).$promise.then(callback);
        }

        function forbidLogin(param, callback) {
            $resource('api/coach/update_status').save(param).$promise.then(callback);
        }

        function registerTrainee(param, callback) {
            $resource('api/coach/register_trainee').save(param).$promise.then(callback);
        }

        function update(param, callback) {
            $resource('api/coach/update').save(param).$promise.then(callback);
        }

        function deleteTrainee(param, callback) {
            $resource('api/coach/delete_trainee').save(param).$promise.then(callback);
        }

        function linkTrainee(trainee_id, callback) {
            $resource('api/coach/link_trainee').save({trainee_id: trainee_id}).$promise.then(callback);
        }
        
        function unlinkTrainee(trainee_id, callback) {
            $resource('api/coach/unlink_trainee').save({trainee_id: trainee_id}).$promise.then(callback);
        }

        function listMyTrainee(callback, simple) {
            var param;
            if(simple) {
                param = {
                    simple : true
                };
            }
            $resource('api/coach/list_my_trainee').get(param).$promise.then(callback);
        }
        return {
            login: login,
            create: create,
            update: update,
            updatePassword: updatePassword,
            listAll: listAll,
            listBase: listBase,
            createBase: createBase,
            deleteBase: deleteBase,
            updateBase: updateBase,
            genCode: genCode,
            resetTraineePassword: resetTraineePassword,
            get: get,
            resetPassword: resetPassword,
            forbidLogin: forbidLogin,
            registerTrainee: registerTrainee,
            deleteTrainee: deleteTrainee,
            linkTrainee: linkTrainee,
            unlinkTrainee: unlinkTrainee,
            listMyTrainee: listMyTrainee
        };
    };

    services.factory('coachService', serviceObject);
});
