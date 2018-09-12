/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $state, $stateParams, $filter, traineeService, socketService) {

        $scope.trainee = {
            name: '...',
            day: 0
        };

        $scope.$on('$stateChangeSuccess', function (event, toState, toParams, fromState, fromParams) {
            $scope.showBackButton = fromState.name === 'query' || fromState.name === 'settings.trainees';
        });

        var today = $filter('date')(new Date(), 'yyyy-MM-dd');
        /**
         * 页面加载时获取用户资料
         */
        traineeService.get($stateParams.trainee_id, function (resp) {

            if (resp.trainee) {
                $scope.trainee = resp.trainee;

                // 获取运动员当日的训练状态
                if ($scope.trainee.last_train_date === today) {
                    if ($scope.trainee.last_status === '1') {
                        $scope.trainee.state = 'IN';
                    } else if ($scope.trainee.last_status === '2') {
                        $scope.trainee.state = 'OUT';
                    }
                } else {
                    $scope.trainee.state = false;
                }

                initWebSocket();

            } else {
                $state.go('home');
            }
        });

        $scope.$on('$stateChangeSuccess', function () {
            $scope.traineeCurrentView = $state.current.name;
            $scope.stateName = $state.$current.name;

        });
        
        $scope.totalActions = 0;

        // 监听子控制器发送 更新训练动作数量 消息
        $scope.$on('updateTotalActions', function (event, val) {
            $scope.totalActions = val;
            event.stopPropagation();
        });

        // websocket 监听运动员入场情况，如果是当前运动员填完运动前评估表入场，更新此运动员状态
        function initWebSocket() {
            var ws = socketService.open($scope);

            // 当服务器端有响应消息时触发
            ws.onmessage = function (e) {
                var data = JSON.parse(e.data);
                var type = data.type;
                switch (type)
                {
                    case "trainee_in":
                        if (data.trainee.id === $scope.trainee.id) {
                            $scope.trainee.state = 'IN';
                            $scope.trainee.last_train_date = today;
                            $scope.trainee.last_status = '1';
                            $scope.$digest();
                        }
                        break;
                    case "trainee_out":
                        if (data.trainee_id === $scope.trainee.id) {
                            $scope.trainee.state = 'OUT';
                            $scope.trainee.last_status = '2';
                            $scope.$digest();
                        }
                        break;
                }
            };
        }


    };

    am.controller('coachTraineeCtrl', controllerObject);
});


