/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {
    
    var controllerObject = function($scope, traineeService, accountService) {
        var form = $scope.form = {};
        
        accountService.isAlive('trainee.home');
        
        function login() {
            traineeService.login(form);
        }
        
        $scope.login = login;
    };
    
    am.controller('traineeLoginCtrl', controllerObject);
});


