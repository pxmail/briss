/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $ionicHistory, $state, $rootScope) {
        $scope.tab = 'data';
        $scope.setTab = function (tab) {
            $scope.tab = tab;
        };
        
        $scope.goHome = function () {
            $ionicHistory.nextViewOptions({
                disableBack: true
            });

            $state.go('trainee.home');
        };
        
        $scope.$on('$stateChangeSuccess', function() {
            $rootScope.currentState = $state.current.name.replace('trainee.', '');
        });
        
    };

    am.controller('traineeCtrl', controllerObject);
});


