/* 
 * (C) 2016 EZ Sport Ltd.
 * 
 * UI说要做一个炫酷的星星按件
 */
define(['../modules', 'jquery'], function (directives) {

    var directiveObject = function () {

        return {
            restrict: 'A',
            scope: {
                fullMode: '<',
                endTrain: '<',
                readonly: '<',
                date: '<',
                form: '=ezEvaluation'
            },
            templateUrl: 'page/inc/evaluation_form.html',
            controller: function ($scope) {
                var form = $scope.form;
                $scope.isCoach = window.__terminal === 'coach';
                
                $scope.$watch('form', function (_form) {
                    if (_form) {
                        _form.morning_pulse = parseInt(_form.morning_pulse);
                        if(_form.morning_pulse === 0) {
                            delete _form.morning_pulse;
                        }
                        
                        _form.hrv_before = parseInt(_form.hrv_before);
                        if(_form.hrv_before === 0) {
                            delete _form.hrv_before;
                        }
                        
                        _form.hrv_after = parseInt(_form.hrv_after);
                        if(_form.hrv_after === 0) {
                            delete _form.hrv_after;
                        }
                        
                        _form.hrv_cold = parseInt(_form.hrv_cold);
                        if(_form.hrv_cold === 0) {
                            delete _form.hrv_cold;
                        }
                        
                        if(typeof _form.pain === 'string') {
                            _form.pain = JSON.parse(_form.pain);
                        }
                    }
                });

                function addPain() {
                    form.pain.push({});
                }

                function removePain(index) {
                    if (form.pain.length > 1) {
                        form.pain.splice(index, 1);
                    }
                }
                $scope.addPain = addPain;
                $scope.removePain = removePain;
                
            }
        };

    };

    directives.directive('ezEvaluation', directiveObject);

});



