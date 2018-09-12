define(['../modules', 'jquery'], function (services) {
    'use strict';

    var serviceObject = function ($ionicPopup) {
//        var timer;
        function info(msg) {
            $ionicPopup.alert({
                title: '错误',
                template: msg
            });

//            $timeout.cancel(timer);
//
//            $('#msg').text(msg).addClass('show');
//
//            timer = $timeout(function () {
//                $('#msg').removeClass('show');
//            }, 4000);
        }

        return {
            info: info
        };
    };

    services.factory('pageService', serviceObject);
});
