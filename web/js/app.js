define([
    'angular',
    'ngResource',
    'ngAnimate',
    'ngSanitize',
    'ionicAngular',
    'uiRouter',
    'controllers/index',
    'services/index',
    'filters/index',
    'directives/index'
], function (angular) {
    return angular.module('app', [
        'ngResource',
        'ngAnimate',
        'ngSanitize',
        'ionic',
        'ui.router',
        'app.modules'
    ]);
});
