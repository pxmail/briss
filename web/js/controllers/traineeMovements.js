/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {
    
    var controllerObject = function($scope, trainingService) {
        var movements;
    	trainingService.getMovements(function(_movements) {
            movements = $scope.movements = _movements;
        });
        
    };
    
    am.controller('traineeMovementsCtrl', controllerObject);
});


