/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules', 'lib/chartjs/Chart.min'], function (am, Chart) {

    var controllerObject = function ($scope, $stateParams, trainingService, $filter, evaluationService, accountService) {
        $scope.title = '正在生成报表';
        $scope.is_coach = window.__terminal === 'coach';

        if (!$stateParams.trainee_id) {
            $stateParams.trainee_id = accountService.getMyUid();
        }

        var movement;
        if ($stateParams.movement_id === 'eval') {
            $scope.mode = 'evaluations';
            $scope.title = '训练状态报表';
        } else {
            trainingService.getMovements(function (m, movementsMap) {
                $scope.mode = 'actions';
                movement = $scope.movement = movementsMap[$stateParams.movement_id];
                $scope.title = movement.name + '报表';
            });

        }

        // 创建一个日期数组，用于加载数据
        var dates = [], steps = [6, 29, 59, 119, 364];
        var now = new Date().valueOf();
        var today = $filter('date')(now, 'yyyy-MM-dd');
        // 六天前日期
        for (var i in steps) {
            dates.push($filter('date')(now - steps[i] * 86400 * 1000, 'yyyy-MM-dd'));
        }

        // 最后一行是获取所有数据，由于系统是 2016-07-01 之后部署的，因此数据库中不应该存在此日期之前的数据
        // 因此传入 start_time = 2016-07-01 end_time = 今天 即可获取到所有数据
        dates.push('2016-07-01');

        $scope.currentIndex = -1;
        function setReportTab(index) {
            $scope.currentIndex = index;
            // 导出Excel操作的传递参数
            var params = [
                'trainee_id=' + $stateParams.trainee_id,
                'movement_id=' + $stateParams.movement_id,
                'date_start=' + dates[index],
                'date_end=' + today,
                'access_token=' + localStorage.access_token
            ];
            $scope.post_params = params.join('&');
            loadData();
        }

        function loadData() {
            // 状态评估表另外获取
            delete $scope.data;
            if ($stateParams.movement_id === 'eval') {
                evaluationService.listByDateRange($stateParams.trainee_id, dates[$scope.currentIndex], today, evalCallback);
                function evalCallback(resp) {
                    $scope.data = resp.evaluation;
                }
            } else {
                trainingService.getData($stateParams.trainee_id, $stateParams.movement_id, dates[$scope.currentIndex], today, dataCallback);
                function dataCallback(resp) {
                    $scope.data = resp.data;
                }
            }
        }

        var standardColors = [
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
            'rgba(96, 125, 139, 1)'
        ];

        function getColor(idx) {
            return standardColors[ idx % standardColors.length];
        }

        var reportChart;
        $scope.$watch('data', function (rawData) {

            if (rawData && rawData.length > 0) {

                var chartData = {
                    labels: [],
                    datasets: []
                };

                // 评估表特别处理
                if ($stateParams.movement_id === 'eval') {
                    $.each(rawData, function () {
                        var row = this;
                        chartData.labels.push(row.create_date);
                        var i = -1;

                        i++;
                        if (!chartData.datasets[i]) {
                            chartData.datasets.push({
                                label: '自我状态评价',
                                borderColor: getColor(i),
                                backgroundColor: 'transparent',
                                data: [row.self_rating]
                            });
                        } else {
                            chartData.datasets[i].data.push(row.self_rating);
                        }


                        i++;
                        if (!chartData.datasets[i]) {
                            chartData.datasets.push({
                                label: '训练欲望',
                                borderColor: getColor(i),
                                backgroundColor: 'transparent',
                                data: [row.desire]
                            });
                        } else {
                            chartData.datasets[i].data.push(row.desire);
                        }


                        i++;
                        if (!chartData.datasets[i]) {
                            chartData.datasets.push({
                                label: '睡眠质量',
                                borderColor: getColor(i),
                                backgroundColor: 'transparent',
                                data: [row.sleep]
                            });
                        } else {
                            chartData.datasets[i].data.push(row.sleep);
                        }


                        i++;
                        if (!chartData.datasets[i]) {
                            chartData.datasets.push({
                                label: '食欲',
                                borderColor: getColor(i),
                                backgroundColor: 'transparent',
                                data: [row.appetite]
                            });
                        } else {
                            chartData.datasets[i].data.push(row.appetite);
                        }

                        i++;
                        if (!chartData.datasets[i]) {
                            chartData.datasets.push({
                                label: 'OmegaWave',
                                borderColor: getColor(i),
                                backgroundColor: 'transparent',
                                data: [row.omega_wave]
                            });
                        } else {
                            chartData.datasets[i].data.push(row.omega_wave);
                        }

                        i++;
                        if (!chartData.datasets[i]) {
                            chartData.datasets.push({
                                label: '训练态度',
                                borderColor: getColor(i),
                                backgroundColor: 'transparent',
                                data: [row.attitude]
                            });
                        } else {
                            chartData.datasets[i].data.push(row.attitude);
                        }

                        i++;
                        if (!chartData.datasets[i]) {
                            chartData.datasets.push({
                                label: '训练质量',
                                borderColor: getColor(i),
                                backgroundColor: 'transparent',
                                data: [row.quality]
                            });
                        } else {
                            chartData.datasets[i].data.push(row.quality);
                        }

                    });
                    
                } else {
//                    $.each(movement.format, function (fieldName) {
//                        if (fieldName !== 'group_id') {  // 跳过组数
//                            chartData.labels.push(this.title);
//                        }
//                    });

                    $.each(rawData, function () {
                        var row = this;
                        chartData.labels.push(row.training_date);

                        var i = -1;
                        $.each(movement.format, function (fieldName) {
                            var format = this;
                            if (fieldName !== 'group_id') {  // 跳过组数
                                i++;

                                if (!chartData.datasets[i]) {
                                    chartData.datasets.push({
                                        label: format.title,
                                        borderColor: getColor(i),
                                        backgroundColor: 'transparent',
                                        data: [row[fieldName]]
                                    });
                                }
                            } else {
                                chartData.datasets[i].data.push( row[fieldName] );
                            }
                        });
//
//                        var _data = [];
//
//                        $.each(movement.format, function (fieldName) {
//                            if (fieldName !== 'group_id') {
//                                _data.push(row[fieldName]);
//                            }
//                        });
//
//                        var color = getColor(idx);
//                        chartData.datasets.push({
//                            label: row.training_date,
//                            borderColor: color,
//                            backgroundColor: 'transparent', // color.replace('1)','0.6)'),
//                            data: _data
//                        });
                    });
                }

                if (!reportChart) {
                    reportChart = Chart.Line($('#report-chart-canvas'), {
                        data: chartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            elements: {
                                line: {
                                    tension: 0
                                }
                            }
                        }
                    });
                } else {
                    reportChart.data.labels = chartData.labels;
                    reportChart.data.datasets = chartData.datasets;
                    reportChart.update();
                }
            }
        });


        // 默认第一个标签显示
        setReportTab(0);

        $scope.setReportTab = setReportTab;
        $scope.loadData = loadData;
    };

    am.controller('traineeReportCtrl', controllerObject);
});


