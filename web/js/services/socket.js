define(['../modules', 'jquery', 'config'], function (services) {
    'use strict';

    var serviceObject = function ($window) {
    	function open(scope) {
            // 创建websocket
            var ws = new WebSocket($window.config.socketUrl);
            // 当socket连接打开时触发
            ws.onopen = function () {
                console.log("服务器连接成功");
            };          
            
            ws.onclose = function () {
            	console.log("服务器连接断开");
            };
            
            scope.$on('$destroy', function() {
            	ws.close();
            });
            
            return ws;
    	}

        return {
            open : open
        };
    };

    services.factory('socketService', serviceObject);
});