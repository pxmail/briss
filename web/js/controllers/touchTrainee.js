/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $state, $stateParams, $ionicPopup, $timeout, traineeService, touchService) {

        $scope.trainee = {
            name: '...',
            day: 0
        };

        $scope.$on('$stateChangeSuccess', function (event, toState, toParams, fromState, fromParams) {
            $scope.showBackButton = fromState.name === 'query';
        });

        $scope.totalActions = 0;
        // 监听子控制器发送 更新训练动作数量 消息
        $scope.$on('updateTotalActions', function (event, val, digest) {
            $scope.totalActions = val;
            // 触屏端 websocks 获取最新的动作数据，如果发生变化时，需要通知 $scope 更新
            // 否则不能正确更新 "完成动作数"
            if (digest) {
                $scope.$digest();
            }
            event.stopPropagation();
        });

        /**
         * 页面加载时获取用户资料
         */
        traineeService.get($stateParams.trainee_id, function (resp) {

            if (resp.trainee) {
                $scope.trainee = resp.trainee;
            } else {
                $state.go('home');
            }
        });

        $scope.$on('$stateChangeSuccess', function () {
            $scope.traineeCurrentView = $state.current.name;
            $scope.stateName = $state.$current.name;

        });

        /**
         * 跳转到运动员资料页面，须检查验证码鉴权
         */

        var timer;
        function setSessionClearTimer() {

            $timeout.cancel(timer);

            timer = $timeout(function () {
                touchService.isSessionValid = false;
                timer = null;
            }, 300000);
        }


        $scope.getProfile = function () {

            $scope.auth = {};
            var saving = false;
            // 如果已经设置了清除 session 的 timer 清除后重新设置一遍，保证最后一次查看资料后5分钟内无须再
            if (timer) {
                setSessionClearTimer();
            }

            if (touchService.isSessionValid) {
                $state.go('trainee.profile');
            } else {
                var myPopup = $ionicPopup.show({
                    template: '<input type="number" data-ng-model="auth.code" minlength="6" maxlength="6" placeholder="6位数字验证码" style="text-align:center;"><div class="check-error" data-ng-class="{show: error}">{{error}}</div>',
                    title: '输入验证码',
                    subTitle: '验证码请联系教练员',
                    scope: $scope,
                    buttons: [
                        {text: '取消'},
                        {
                            text: '确定',
                            type: 'button-positive',
                            onTap: function (e) {

                                e.preventDefault();
                                if ($scope.auth.code) {

                                    if (saving) {
                                        return;
                                    }

                                    saving = true;

                                    touchService.authorize($scope.auth.code, function (resp) {
                                        saving = false;

                                        if (resp.success) {
                                            touchService.isSessionValid = true;
                                            // 验证码5分钟后失效
                                            setSessionClearTimer();

                                            myPopup.close();
                                            $state.go('^.profile');
                                        } else if (resp.success === false) {
                                            $scope.error = '验证码错误';
                                            $timeout(function () {
                                                delete $scope.error;
                                            }, 3000);
                                        }
                                    });
                                }
                            }
                        }
                    ]
                });

            }
        };
    };

    am.controller('touchTraineeCtrl', controllerObject);
});


