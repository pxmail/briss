/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $state, coachService, pageService, accountService) {
        $scope.form = {};
        $scope.my_uid = accountService.getMyUid();

        if ($state.is('settings.coaches')) {

            coachService.listAll(function (resp) {
                $scope.coaches = resp.coaches;
            });
        }

        function create() {
            coachService.create($scope.form, function (resp) {
                if (resp.id) {
                    $state.go('settings.coaches');
                }
            });
        }

        function genCode() {
            coachService.genCode(function (resp) {
                if (resp.code) {
                    var code = resp.code.toString();
                    $scope.touchCode = code.split("");
                }
            });
        }

        $scope.create = create;
        $scope.genCode = genCode;
    };

    am.controller('coachManagementCtrl', controllerObject);
});
