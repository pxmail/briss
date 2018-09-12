define(['../modules'], function (services) {
    'use strict';

    var serviceObject = function ($resource) {
        function authorize(vcode, callback) {
            $resource('api/touch/check_vcode').get({code: vcode}).$promise.then(callback);
        }

        return {
            authorize: authorize
        };
    };

    services.factory('touchService', serviceObject);
});
