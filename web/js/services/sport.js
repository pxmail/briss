define(['../modules', 'jquery'], function (services) {
    'use strict';

    var serviceObject = function ($resource, $state, $ionicHistory) {
        function lists(callback) {
            $resource('api/sport/lists').get().$promise.then(callback);
        }
        
        return {
        	lists: lists
        };
    };
    
    services.factory('sportService', serviceObject);
});