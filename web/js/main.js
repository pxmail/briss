/**
 * requirejs 
 */

require.config({
    baseUrl: 'js',
    paths: {
        'jquery': 'lib/jquery/jquery-3.0.0.min',
        'config': 'config',
        'angular': 'lib/ionic/js/angular/angular.min',
        'ngResource': 'lib/ionic/js/angular/angular-resource.min',
        'ngAnimate': 'lib/ionic/js/angular/angular-animate.min',
        'ngSanitize': 'lib/ionic/js/angular/angular-sanitize.min',
        'domReady': 'lib/requirejs/require-domready-min',
        'uiRouter': 'lib/ionic/js/angular-ui/angular-ui-router.min',
        'ionic': 'lib/ionic/js/ionic.min',
        'ionicAngular': 'lib/ionic/js/ionic-angular.min',
        'bootstrap': 'bootstrap',
        'app': 'app'
    },
    shim: {
        'angular': {
            exports: 'angular'
        },
        'ngResource': {
            deps: ['angular'],
            exports: 'ngResource'
        },
        'ngAnimate': {
            deps: ['angular'],
            exports: 'ngAnimate'
        },
        'ngSanitize': {
            deps: ['angular'],
            exports: 'ngSanitize'
        },
        'config': {
            exports: 'config'
        },
        'uiRouter': {deps: ['angular']},
        'ionic': {deps: ['angular'], exports: 'ionic'},
        'ionicAngular': {deps: ['ionic', 'uiRouter', 'ngAnimate', 'ngSanitize']}
    },
    priority: [
        'jquery',
        'angular',
        'ionic'
    ],
    deps: ['bootstrap'],
    waitSeconds: 200
//    urlArgs: "t=" + (new Date()).getTime()
});