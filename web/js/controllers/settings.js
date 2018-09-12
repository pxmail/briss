/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $state, accountService) {
        $scope.isSupperCoach = accountService.isSupperCoach();
        
        $scope.$on('$stateChangeSuccess', function() {
            $scope.currentState = $state.current.name.split('.')[1];
        });
        
    };

    am.controller('settingsCtrl', controllerObject);
});