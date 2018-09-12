/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $ionicHistory,
            evaluationService, accountService) {
        var trainee_id = accountService.getMyUid();
        evaluationService.get(trainee_id, null, function (resp) {
            $scope.form = resp.evaluation;
        });

        $scope.isEndTrain = true;

        function save() {
            evaluationService.finish($scope.form, function (resp) {
                if (resp.success) {
                    $ionicHistory.goBack();
                }
            });
        }

        $scope.save = save;

    };

    am.controller('traineeEndTrainCtrl', controllerObject);
});