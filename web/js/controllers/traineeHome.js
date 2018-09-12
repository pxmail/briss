/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $filter, trainingService, accountService, socketService) {
        $scope.isHidden = [];

        /**
         * 动作ID对应动作名称
         */
        var movementsMap = {};
        trainingService.getMovements(function (movements) {
            $.each(movements, function () {
                $.each(this, function () {
                    movementsMap[this.id] = this.name;
                });
            });
        });

        var trainee_id = accountService.getMyUid();

        var today = $filter('date')(new Date().valueOf(), 'yyyy-MM-dd');
        trainingService.listByTrainee(trainee_id, null, function (resp) {
            // 训练数据
            $scope.trainings = resp.trainings;
            // 当天是否填写了评估表
            $scope.entered = resp.last_train_date === today;
            // 最后一次的状态
            $scope.status = resp.last_status;
            
            angular.forEach($scope.trainings, function (training) {
                training.movement_name = movementsMap[training.movement_id];
            });
        });

        var ws = socketService.open($scope);
        
        // 当服务器端有响应消息时触发
        ws.onmessage = function (e) {
            var data = JSON.parse(e.data);
            var type = data.type;

            if (type === "new_data" && trainee_id === data.trainee_id) {
                $scope.trainings.push(data.training);
                angular.forEach($scope.trainings, function (training) {
                    training.movement_name = movementsMap[training.movement_id];
                });
                $scope.$digest();
            }
        };

        function toggleDetails(row) {
            row.details = !row.details;
        }

        $scope.toggleDetails = toggleDetails;
    };

    am.controller('traineeHomeCtrl', controllerObject);
});


