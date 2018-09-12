/* 
 * (C) 2016 EZ Sport Ltd.
 * 
 * 填写数据表单指令
 */
define(['../modules', 'jquery'], function (directives) {

    var directiveObject = function () {

        return {
            restrict: 'A',
            scope: {
                form: '=ezDataForm',
                magicFn: '<'        // 魔术方法，用于特殊数据格式的回调，比如103（10秒冲刺训练，计算总距离）
            },
            templateUrl: 'page/inc/data_form.html',
            controller: function ($scope) {
                $scope.$watch('form.movement_id', function (val) {
                    // 5 x 30s 高强度间歇性训练，自动计算总距离
                    if (val === 101) {
                        $scope.$watch('form', calc_5x30_total, true);
                    } else if (val === 103) {
                        $scope.$watch('form', calc_10s, true);
                    }
                });

                /**
                 * 5x30s 冲刺计算总距离
                 */
                function calc_5x30_total() {
                    var total = 0, val;
                    for (var i = 1; i <= 5; i++) {

                        val = parseInt($scope.form['number_' + i]);

                        if (!isNaN(val)) {
                            total += val;
                        }
                    }

                    if (total) {
                        $scope.form.number_6 = total;
                    } else {
                        delete $scope.form.number_6;
                    }
                }

                /**
                 * 10s 冲刺计算单次冲次距离及总距离
                 */
                function calc_10s() {
                    var valStart = parseInt($scope.form.number_1);
                    var valEnd = parseInt($scope.form.number_2);

                    // 计算本次冲刺距离
                    var differ = 0;

                    if (!isNaN(valStart) && !isNaN(valEnd)) {
                        differ = valEnd - valStart;
                    }

                    if (differ) {
                        $scope.form.number_3 = differ;
                        $scope.form.number_4 = $scope.magicFn($scope.form.group_id, $scope.form.id) + differ;
                    } else {
                        delete $scope.form.number_3;
                        delete $scope.form.number_4;
                    }
                    
                    
                }
            }
        };

    };

    directives.directive('ezDataForm', directiveObject);

});



