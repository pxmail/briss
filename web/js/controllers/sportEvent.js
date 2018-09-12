/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $ionicHistory, traineeService, sportService) {
    	$scope.sports = [];
    	//运动项目列表
    	sportService.lists(function (resp){
    		$scope.sports = resp.sports;
    	});
    	
        function selectItem(sport) {
        	traineeService.trainee.sport = sport.name;
        	traineeService.trainee.sport_id = sport.id;
        	$ionicHistory.goBack();
        }
        
        $scope.selectItem = selectItem;
    };

    am.controller('sportEventCtrl', controllerObject);
});