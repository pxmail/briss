/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {
    
    var controllerObject = function($scope, $ionicHistory, $rootScope, $state, accountService) {
        accountService.isAlive();

        $rootScope.defaultAvatar = 'image/avatar.svg';
        $scope.goBack = function() {
            $ionicHistory.goBack();
        };
        
        $scope.logout = accountService.logout;
    };
    
    am.controller('traineeMainCtrl', controllerObject);
});


