/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $ionicScrollDelegate, $ionicHistory, evaluationService, pageService, accountService) {
        var uid = accountService.getMyUid();

        var form = $scope.form = {};
        
        function resetForm() {
            form.trainee_id = uid;
            form.self_rating = 0;
            form.desire = 0;
            form.sleep = 0;
            form.appetite = 0;
            form.omega_wave = 0;
            form.pain = [{}];
            form.rpe_before = 0;
            form.morning_pulse = null;
            form.hrv_before = null;
        }

        function save() {
            $scope.saving = true;
            var checkResult = evaluationService.checkFormBefore(form);

            if (checkResult !== true) {
                pageService.info(checkResult + '未填写');
                return;
            }

            evaluationService.create(form, function (resp) {
                $scope.saving = false;
                if (resp.success) {
                    $ionicHistory.goBack();
                }
            });


        }

        resetForm();    // controller 加载时对 form 实行一次初始化

        $scope.$watch('form.pain', function () {
            $ionicScrollDelegate.resize();
        }, true);
        
        $scope.save = save;
    };

    am.controller('traineeEvaluationCtrl', controllerObject);
});


