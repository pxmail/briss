define(['app'], function (app) {
    'use strict';

    app.factory('app.httpInterceptor', function ($q, $rootScope) {
        var handleRequest = function (config) {
            if (config.url.indexOf('api') >= 0) {
                config.headers['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
                if (localStorage.access_token) {
                    config.headers['x-access-token'] = localStorage.access_token;
                }

                if (config.data && typeof (config.data) === 'object') {
                    var params = [];
                    for (var key in config.data) {
                        if (key.indexOf('$') >= 0) {
                            continue;
                        }
                        if (typeof (config.data[key]) === 'object') {
                            params.push(encodeURIComponent(key) + '=' + encodeURIComponent(JSON.stringify(config.data[key])));
                        } else {
                            params.push(encodeURIComponent(key) + '=' + encodeURIComponent(config.data[key]));
                        }
                    }

                    config.data = params.join('&');

                }
//                config.url = 'https://briss.ezsport.com.cn/' + config.url;
            }

            return config;
        };

        var handleRequestError = function (rejection) {
            return $q.reject(rejection);
        };

        var handleResponse = function (response) {
            var type = response.headers()['content-type'];
            if (type && type.indexOf('application/json') !== -1) {

                // 服务器返回错误
                if (response.data.error) {
                    $rootScope.$broadcast('apiError', response.data.error);
                }
            }

            return response;
        };
        var handleResponseError = function (rejection) {
            return $q.reject(rejection);
        };


        return {
            request: handleRequest,
            requestError: handleRequestError,
            response: handleResponse,
            responseError: handleResponseError
        };
    });

//    app.config(['$ionicConfigProvider', function ($ionicConfigProvider) {
//            $ionicConfigProvider.tabs.position('bottom'); // other values: top
//        }]);

    return app.config(function ($httpProvider) {
        $httpProvider.interceptors.push('app.httpInterceptor');
    });
});
