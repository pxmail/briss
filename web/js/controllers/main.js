/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $ionicHistory, $rootScope, $state, accountService) {
        accountService.isAlive();

        $rootScope.__terminal = window.__terminal;

        function gotoTraineeHome(trainee_id) {
            $state.go('trainee.home', {trainee_id: trainee_id});
        }

        $rootScope.defaultAvatar = 'image/avatar.svg';
        $scope.goBack = function () {
            $ionicHistory.goBack();
        };

        $scope.goHome = function () {
            $ionicHistory.nextViewOptions({
                disableBack: true
            });

            $state.go('home');
        };

        $scope.logout = accountService.logout;

        $scope.gotoTraineeHome = gotoTraineeHome;

    };

    am.controller('mainCtrl', controllerObject);
});


