/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {
    
    var controllerObject = function($scope, $state, coachService, accountService) {
        accountService.isAlive('home');
        
        var form = $scope.form = {};
        
        function login() {
            coachService.login(form);
        }
        
        $scope.login = login;
    };
    
    am.controller('coachLoginCtrl', controllerObject);
});


