/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules', 'lib/chartjs/Chart.min'], function (am, Chart) {

    var controllerObject = function ($scope, $state, $timeout, $ionicPopup, $filter, $rootScope, coachService, traineeService, trainingService, socketService) {
        traineeService.listActive(function (trainees) {
            $scope.trainees = trainees;
            $scope.initWebSocket();
        });

        traineeService.listTrainee(function (amount) {
            $scope.totalTrainees = amount;
        });

        var myTraineeIds;
        if ($rootScope.__terminal === 'coach') {
            coachService.listMyTrainee(function (resp) {
                myTraineeIds = resp.trainee_ids;
            }, true);
        }


        /**
         * 映射运动Id和运动名称的对应关系
         */
        var movementsMap;
        trainingService.getMovements(function (_movements, _movementsMap) {
            movementsMap = _movementsMap;
        });

        var statChart;
        function statsCallback(resp) {
            if (resp.stats) {
                var chartData = {
                    labels: [],
                    datasets: [
                        {
                            label: "训练人次",
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(255,99,132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(75,192,192, 0.2)',
                                'rgba(0, 188, 212, 0.2)',
                                'rgba(0, 150, 136, 0.2)',
                                'rgba(76, 175, 80, 0.2)',
                                'rgba(139, 195, 74, 0.2)',
                                'rgba(205, 220, 57, 0.2)',
                                'rgba(255, 235, 59, 0.2)',
                                'rgba(255, 193, 7, 0.2)',
                                'rgba(255, 152, 0, 0.2)',
                                'rgba(255, 87, 34, 0.2)',
                                'rgba(121, 85, 72, 0.2)',
                                'rgba(158, 158, 158, 0.2)',
                                'rgba(96, 125, 139, 0.2)',
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255,99,132,1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(255,99,132,1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(75,192,192, 1)',
                                'rgba(0, 188, 212, 1)',
                                'rgba(0, 150, 136, 1)',
                                'rgba(76, 175, 80, 1)',
                                'rgba(139, 195, 74, 1)',
                                'rgba(205, 220, 57, 1)',
                                'rgba(255, 235, 59, 1)',
                                'rgba(255, 193, 7, 1)',
                                'rgba(255, 152, 0, 1)',
                                'rgba(255, 87, 34, 1)',
                                'rgba(121, 85, 72, 1)',
                                'rgba(158, 158, 158, 1)',
                                'rgba(96, 125, 139, 1)',
                                'rgba(255,99,132,1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(255,99,132,1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1,
                            data: []
                        }
                    ]
                };

                $.each(resp.stats, function (index, val) {
                    chartData.labels.push(index);
                    chartData.datasets[0].data.push(val);
                });

                if (!statChart) {
                    statChart = new Chart($('#stat-chart-canvas'), {
                        type: 'bar',
                        data: chartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                            },
                            legend: {
                                display: false
                            }
                        }
                    });
                } else {
                    statChart.data.labels = chartData.labels;
                    statChart.data.datasets = chartData.datasets;
                    statChart.update();
                }
            }
        }

        $scope.statType = null;
        var now = new Date();
        var history;
        var today = $filter('date')(now, 'yyyy-MM-dd');

        function stat(type) {
            // 每次切换前，重置用于计算"上一周/下一周"，"上一月/下一月"，"上一年/下一年" 的基础日期
            history = new Date();
            $scope.statType = type;

            var date;
            if (type === 'week') {
                date = today;
            } else if (type === 'month') {
                date = today.substring(0, 7);
            } else if (type === 'year') {
                date = today.substring(0, 4);
            }

            traineeService.stats(date, statsCallback);
        }

        $scope.stat = stat;
        // 上一（周/月/年）翻页
        function statPrev(type) {
            var date;
            
            if (type === 'week') {
                history.setTime(history.valueOf() - 604800000);
                date = $filter('date')(history, 'yyyy-MM-dd');
            } else if (type === 'month') {
                history.setDate(-1);
                date = $filter('date')(history, 'yyyy-MM');
            } else if (type === 'year') {
                date = history.getFullYear() - 1;
            }

            traineeService.stats(date, statsCallback);
        }

        $scope.statPrev = statPrev;

        // 下一（周/月/年）翻页
        function statNext(type) {
            var date;
            if (type === 'week') {
                history.setTime(history.valueOf() + 604800000);
                date = $filter('date')(history, 'yyyy-MM-dd');
            } else if (type === 'month') {
                history.setDate(32);
                date = $filter('date')(history, 'yyyy-MM');
            } else if (type === 'year') {
                date = history.getFullYear() + 1;
                history.setFullYear(date);
            }

            traineeService.stats(date, statsCallback);
        }

        $scope.statNext = statNext;

        var statPopup;
        $scope.closeStat = function () {
            statChart.destroy();
            statChart = null;
            statPopup.close();
        };


        $scope.showStat = function () {
            statPopup = $ionicPopup.show({
                template: '<button data-ng-click="closeStat()" class="close-button button button-icon icon ion-android-close"></button>\n\
                        <div class="chart-container"><canvas id="stat-chart-canvas"></canvas></div>\n\
                        <div class="chart-buttons row">\n\
                            <div>\n\
                                <button data-ng-click="statPrev(\'week\')" data-ng-class="{\'highlight-set\' : statType === \'week\'}" data-ng-show="statType === \'week\'">上一周</button><button data-ng-click="stat(\'week\')" data-ng-class="{\'highlight-set\' : statType === \'week\'}">本周</button><button data-ng-click="statNext(\'week\')" data-ng-class="{\'highlight-set\' : statType === \'week\'}" data-ng-show="statType === \'week\'">下一周</button><button data-ng-click="statPrev(\'month\')" data-ng-class="{\'highlight-set\' : statType === \'month\'}" data-ng-show="statType === \'month\'">上一月</button><button data-ng-click="stat(\'month\')" data-ng-class="{\'highlight-set\' : statType === \'month\'}">本月</button><button data-ng-click="statNext(\'month\')" data-ng-class="{\'highlight-set\' : statType === \'month\'}" data-ng-show="statType === \'month\'">下一月</button><button data-ng-click="statPrev(\'year\')" data-ng-class="{\'highlight-set\' : statType === \'year\'}" data-ng-show="statType === \'year\'">上一年</button><button data-ng-click="stat(\'year\')" data-ng-class="{\'highlight-set\' : statType === \'year\'}">本年</button><button data-ng-click="statNext(\'year\')" data-ng-class="{\'highlight-set\' : statType === \'year\'}" data-ng-show="statType === \'year\'">下一年</button>\n\
                            </div>\n\
                            <div class="total-users flex">运动员总数：{{totalTrainees}}</div>\n\
                        </div>',
                title: '入场训练人次统计',
                cssClass: 'stat-popup',
                scope: $scope
            });


            stat('week');
        };

        function initWebSocket() {
            var ws = socketService.open($scope);

            // 当服务器端有响应消息时触发
            ws.onmessage = function (e) {
                var data = JSON.parse(e.data);
                var type = data.type;
                switch (type)
                {
                    case "trainee_in":
                        // 如果有自己的学员列表，检查是否在列表中
                        if (myTraineeIds) {
                            if ($.inArray(data.trainee.id, myTraineeIds) === -1) {
                                break;
                            }
                        }

                        $scope.trainees.unshift(data.trainee);
                        $scope.$digest();
                        break;
                    case "trainee_out":
                        window.angular.forEach($scope.trainees, function (item, index) {
                            if (item.id === data.trainee_id) {
                                $scope.trainees.splice(index, 1);
                                $scope.$digest();
                            }
                        });
                        break;
                    case "new_data":
                        window.angular.forEach($scope.trainees, function (item) {
                            if (item.id === data.trainee_id) {
                                item.movement_name = movementsMap[data.movement_id].name;
                                $timeout(function () {
                                    delete item.movement_name;
                                }, 8000);
                                $scope.$digest();
                            }
                        });
                        break;
                }
            };
        }

        function gotoQuery() {
            $state.go('query');
        }

        $scope.gotoQuery = gotoQuery;
        $scope.initWebSocket = initWebSocket;
    };

    am.controller('touchHomeCtrl', controllerObject);
});


