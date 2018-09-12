/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules', 'lib/chartjs/Chart.min'], function (am) {

    var controllerObject = function ($scope, $stateParams, $ionicScrollDelegate, $ionicPopup, $timeout,
            evaluationService, trainingService, accountService) {
        $scope.isHistoryPage = true;

        // 导出Excel操作的传递参数
        var params = [
            'trainee_id=' + $stateParams.trainee_id,
            'training_date=' + $stateParams.date,
            'access_token=' + localStorage.access_token
        ];
        $scope.post_params = params.join('&');

        var traineeId;

        if ($stateParams.trainee_id) {
            traineeId = $stateParams.trainee_id;
        } else {
            traineeId = accountService.getMyUid();
        }

        $scope.date = $stateParams.date;

        evaluationService.get(traineeId, $stateParams.date, function (resp) {
            var form = resp.evaluation;
//            form.pain = JSON.parse(form.pain);
            $scope.evalForm = form;

        });

        /**
         * 转换运动员的训练记录
         */
        var movementsMap;
        trainingService.getMovements(function (_movements, _movementsMap) {
            movementsMap = _movementsMap;
        });


        trainingService.listByTrainee(traineeId, $stateParams.date, function (resp) {
            if (resp.trainings) {
                $scope.trainings = resp.trainings;

                $scope.$emit('updateTotalActions', $scope.trainings.length);

                angular.forEach($scope.trainings, function (training) {
                    training.movement_name = movementsMap[training.movement_id].name;
                });
            }
        });

        $scope.setTab = function (tabName) {
            $scope.tab = tabName;
            $ionicScrollDelegate.resize();
        };

        $scope.setTab('data');

        function toggleDetails(row) {
            row.details = !row.details;
        }

        $scope.toggleDetails = toggleDetails;


        $scope.previous = {
            data: [],
            cursor: 0,
            current: null
        };

        function showWarning(msg) {
            $('#data-warning-msg').html('<span>' + msg + '</span>');
            $timeout(function () {
                $('#data-warning-msg').html('');
            }, 3000);
        }

        function prevData(e) {
            $('#data-warning-msg').text('');
            e.preventDefault();
            if ($scope.previous.cursor > 0) {
                $scope.previous.cursor--;
                $scope.previous.current = $scope.previous.data[$scope.previous.cursor];
            } else {    // 偿试加载历史数据
                var row = $scope.previous.data[0];
                trainingService.getPrevious(row.id, row.trainee_id, row.movement_id).then(function (resp) {
                    if (resp.training && resp.training.id) {
                        $scope.previous.data.unshift(resp.training);
                        $scope.previous.current = resp.training;
                    } else {
                        showWarning('已是最早一条数据');
                    }
                });
            }
        }


        function nextData(e) {
            $('#data-warning-msg').html('');
            e.preventDefault();
            if ($scope.previous.cursor < $scope.previous.data - 1) {
                $scope.previous.cursor++;
                $scope.previous.current = $scope.previous.data[$scope.previous.cursor];
            } else {
                // 偿试获取之后的数据
                var row = $scope.previous.data[$scope.previous.cursor];
                trainingService.getSubsequent(row.id, row.trainee_id, row.movement_id).then(function (resp) {
                    if (resp.training && resp.training.id) {
                        $scope.previous.data.push(resp.training);
                        $scope.previous.cursor++;
                        $scope.previous.current = resp.training;
                    } else {
                        showWarning('已是最后一条数据');
                    }
                });
            }
        }


        function showHistory(row) {
            var previous = $scope.previous;
            previous.data.length = 0;
            previous.cursor = 0;
            previous.current = null;
            $scope.current = row;

            trainingService.getPrevious(row.id, row.trainee_id, row.movement_id).then(function (resp) {
                var popup;
                if (resp.training && resp.training.id) {
                    previous.data.push(resp.training);
                    previous.current = resp.training;

                    popup = $ionicPopup.show({
                        title: row.movement_name + ' - 历史数据',
                        template: '<div id="data-warning-msg"></div><div class="training-date">本次训练数据：</div><div data-ez-data="current"></div><div class="training-date" style="margin-top: 20px;">历史训练数据：{{previous.current.training_date}}</div><div data-ez-data="previous.current"></div>',
                        scope: $scope,
                        cssClass: 'dlg-history-training',
                        buttons: [{
                                text: '前一组',
                                onTap: prevData
                            },
                            {
                                text: '下一组',
                                onTap: nextData
                            },
                            {
                                text: '关闭'
                            }]
                    });


                } else {
                    popup = $ionicPopup.show({
                        title: '错误',
                        template: '找不到上一次的数据',
                        cssClass: 'info-popup',
                        scope: $scope
                    });

                    $timeout(popup.close, 2000);
                }

            });
        }

        $scope.showHistory = showHistory;

    };

    am.controller('traineeHistoryCtrl', controllerObject);
});


