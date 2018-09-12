/* 
 * (C) 2016 EZ Sport Ltd.
 * 
 * UI说要做一个炫酷的星星按件
 */
define(['../modules', 'jquery', 'lib/chartjs/Chart.min'], function (directives, $, Chart) {

    var directiveObject = function () {

        return {
            restrict: 'A',
            scope: {
                form: '=ezRadar'
            },
            template: '<canvas></canvas>',
            link: function (scope, elm) {
                var ctx = angular.element(elm.children()[0]);
                var chart;

                var options = {
                    responsive: true,
                    maintainAspectRatio: false,
                    scale: {
                        ticks: {
                            beginAtZero: true,
                            max: 7,
                            min: 0
                        }
                    },
                    title: {
                        display: true,
                        fontSize: 16
                    },
                    legend: {
                        display: false
                    }
                };

                var data = {
                    labels: ["自我状态评价", "训练欲望", "睡眠质量", "食欲", "Omegawave测试"],
                    datasets: [
                        {
                            backgroundColor: "rgba(255,99,132,0.2)",
                            borderColor: "rgba(255,99,132,1)",
                            pointBackgroundColor: "rgba(255,99,132,1)",
                            pointBorderColor: "#fff",
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "rgba(255,99,132,1)",
                        }
                    ]
                };

                scope.$watch('form', function (form) {
                    if (form) {

                        options.title.text = form.create_date + ' 雷达图';
                        data.datasets[0].label = form.create_date;
                        data.datasets[0].data = [form.self_rating, form.desire, form.sleep, form.appetite, form.omega_wave];

                        if (form.attitude > 0) {
                            data.labels.push('训练态度');
                            data.datasets[0].data.push(form.attitude);
                        }

                        if (form.quality > 0) {
                            data.labels.push('训练质量');
                            data.datasets[0].data.push(form.quality);
                        }

                        if (chart) {
                            chart.destroy();
                        }

                        chart = new Chart(ctx, {
                            type: 'radar',
                            data: data,
                            options: options
                        });
                    }
                });

            }
        };

    };

    directives.directive('ezRadar', directiveObject);

});



