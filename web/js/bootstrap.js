/**
 * Angular bootstrap, load js application after page initialized
 */

define(
[
    'require',
    'angular',
    'app',
    'route-' + window.__terminal,
    'http'
], function (require, angular) {
    'use strict';

    require(['domReady!'], function (document) {
        angular.bootstrap(document, ['app']);
    });
    
});