define(['../modules'], function (services) {
    'use strict';

    var serviceObject = function ($resource, $rootScope, $state, $ionicHistory, pageService) {

        // 检查用户是否登录，如果没有登录则返回到登录页面
        function isAlive(aliveState) {
            // 由于 accountSerive 服务监听了 "apiError" 事件，只要 is_alive API返回
            // 错误，自动处理错误内容，此函数不做其它处理
            $resource('api/account/is_alive').get().$promise.then(function (resp) {
                if (resp.expire_in) {
                    var u = getUser();
                    // 检查角色id，禁止跨域使用
                    if ((u.role_id === '1' && window.__terminal === 'touch') // 触屏端
                            || (u.role_id === '2' && window.__terminal === 'coach') // 超级教练
                            || (u.role_id === '3' && window.__terminal === 'coach') // 普通教练
                            || (u.role_id === '4' && window.__terminal === 'trainee') // 学员
                            ) {
                        if (aliveState) {
                            $ionicHistory.nextViewOptions({
                                disableBack: true
                            });
                            $state.go(aliveState);
                        }
                    } else {
                        logout();
                    }
                }
            });
        }


        $rootScope.$on('apiError', function (event, error) {
            // 如果发生 access token 错误，跳转到登录页面
            if (error.code === 1005 || error.code === 1008) {
                if (!$state.is('login')) {
                    $ionicHistory.nextViewOptions({
                        disableBack: true
                    });
                    $state.go('login');
                }

                return;
            }

            pageService.info(error.msg);
        });


        function login(param, callback) {
            var api = 'api/' + window.__terminal + '/login';
            $resource(api).save(param).$promise.then(function (resp) {
                if (resp.access_token) {
                    localStorage.access_token = resp.access_token;
                }

                if (callback)
                    callback(resp);
            });
        }

        var user;

        function logout() {
            localStorage.access_token = '';
            delete localStorage.access_token;

            user = null;    // 防止切换用户缓存
            $ionicHistory.clearCache();
            $ionicHistory.clearHistory();

            $ionicHistory.nextViewOptions({
                disableBack: true
            });
            $state.go('login');

        }

        function getUser() {
            if (!user) {
                if (localStorage.access_token && localStorage.access_token.length > 0) {
                    var arr = localStorage.access_token.split('.');
                    if (arr.length > 2) {
                        var base64 = arr[1].replace(/\-/g, '+').replace(/_/g, '/');
                        var objStr = atob(base64);
                        if (objStr) {
                            user = JSON.parse(objStr);
                        }
                    }
                } else {
                    $state.go('login');
                }
            }

            return user;
        }

        function getMyUid() {
            return getUser().uid;
        }

        function isSupperCoach() {
            return getUser().role_id === '2';
        }


        return {
            login: login,
            logout: logout,
            isAlive: isAlive,
            getMyUid: getMyUid,
            isSupperCoach: isSupperCoach,
            qiniu: $resource('api/account/get_qiniu_token')
        };
    };

    services.factory('accountService', serviceObject);
});
