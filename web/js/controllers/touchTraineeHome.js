/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules', 'lib/chartjs/Chart.min'], function (am, Chart) {

    var controllerObject = function ($scope, $stateParams, $filter, $ionicScrollDelegate, $timeout, trainingService, evaluationService, socketService) {

        // 获取训练状态评估
        function getEvaluation() {

            evaluationService.get($stateParams.trainee_id, $stateParams.date, function (resp) {
                var form = resp.evaluation;
                if(!form) {
                    return;
                }
                $scope.evalForm = form;

                var data = {
                    labels: ["自我状态评价", "训练欲望", "睡眠质量", "食欲", "Omegawave测试"],
                    datasets: [
                        {
                            label: "今日训练状态",
                            backgroundColor: "rgba(255,99,132,0.2)",
                            borderColor: "rgba(255,99,132,1)",
                            pointBackgroundColor: "rgba(255,99,132,1)",
                            pointBorderColor: "#fff",
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "rgba(255,99,132,1)",
                            data: [form.self_rating, form.desire, form.sleep, form.appetite, form.omega_wave]
                        }
                    ]
                };

                Chart.defaults.global.legend.display = false;

                new Chart($('canvas'), {
                    type: 'radar',
                    data: data,
                    options: {
                        scale: {
                            ticks: {
                                beginAtZero: true
                            }
                        }
                    }
                });

            });
        }

        getEvaluation();

        var trainings = $scope.trainings = [];

        /**
         * 转换运动员的训练记录
         */
        var movementsMap;
        trainingService.getMovements(function (_movements, _movementsMap) {
            movementsMap = _movementsMap;
            getTrainingData();
        });

        /**
         * 获取运动员当天训练记录
         */
        function getTrainingData() {
            var training_date = $filter('date')(new Date(), 'yyyy-MM-dd');

            trainingService.listByTrainee($stateParams.trainee_id, training_date, function (resp) {
                trainings = $scope.trainings = resp.trainings;
                $scope.$emit('updateTotalActions', $scope.trainings.length);

                angular.forEach(trainings, function (training) {
                    training.movement_name = movementsMap[training.movement_id].name;
                });

                $timeout(function () {
                    $ionicScrollDelegate.resize();
                }, 500);

            });
        }

        var ws = socketService.open($scope);
        
        // 当服务器端有响应消息时触发
        ws.onmessage = function (e) {
            var data = JSON.parse(e.data);
            var type = data.type;

            if ("new_data" === type) {
                if ($stateParams.trainee_id === data.training.trainee_id) {

                    data.training.movement_name = movementsMap[data.training.movement_id].name;
                    
                    $scope.trainings.push(data.training);
                    $scope.$digest();
                    // 发送消息，让页面左侧的"完成动作数"计算增加
                    $scope.$emit('updateTotalActions', $scope.trainings.length, true);
                    
                }
            }
        };
    };

    am.controller('touchTraineeHomeCtrl', controllerObject);
});


