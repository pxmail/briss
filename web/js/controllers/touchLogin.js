/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {
    
    var controllerObject = function($scope, $state, accountService) {
        $scope.code = '';
        
        function loginCallback(resp) {
            $state.go('home');
        }
        
        function login() {
            accountService.login({'code' : $scope.code}, loginCallback);
            
            
        }
        
        $scope.login = login;
    };
    
    am.controller('touchLoginCtrl', controllerObject);
});


