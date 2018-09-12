/* 
 * (C) 2016 EZ Sport Ltd.
 */
define(['../modules'], function (am) {
    
    var controllerObject = function($scope, $state, $stateParams, $ionicPopup, $ionicHistory, coachService, pageService, accountService) {
    	$scope.my_uid = accountService.getMyUid();
    	
        if ($stateParams.coach_id) {
        	var param = {'coach_id': $stateParams.coach_id};
        	coachService.get(param, function(resp) {
        		$scope.coach = resp.coach;
        	});
        }
        
        function resetPassword(coach) {
        	var param = {'coach_id': coach.id};
        	var confirmPopup = $ionicPopup.confirm({
        	     title: '重置密码确认',
        	     template: '确定将密码重置为"ezsport"吗？',
                 okText: '确认',
                 cancelText: '取消'
        	});

    	    confirmPopup.then(function(res) {
	    	     if(res) {
    	        	coachService.resetPassword(param, function(resp){
    	        		if (resp.success) {
    	                    $ionicPopup.alert({
    	                        title: '操作成功',
    	                        template: '教练员' + coach.name + '的账户密码已被重置'
    	                    });
    	        		}
    	        	});
	    	     } 
    	    });
        }
        
        function forbidLogin(coach) {
        	var param = {'coach_id': coach.id, 'status': 1};
        	var confirmPopup = $ionicPopup.confirm({
	       	     title: '禁止用户登录确认',
	       	     template: '您确定要禁止该教练员登录吗?',
	             okText: '确认',
	             cancelText: '取消'
        	});
        	confirmPopup.then(function(res) {
	    	     if(res) {
	    	        	coachService.forbidLogin(param, function(resp){
	    	        		if (resp.success) {
	    	                    $ionicPopup.alert({
	    	                        title: '操作成功',
	    	                        template: '教练员' + coach.name + '的账户已被禁止登录'
	    	                    });
	    	        		}
	    	        	}); 
	    	     }
        	});
        }
        
        function updateProfile(coach) {
        	var param = {'coach_id': coach.id, 'role_id': coach.role_id};
        	coachService.update(param, function(resp){
        		if (resp.coach_id) {
        			$ionicHistory.goBack();
        		}
        	});
        }
        
        $scope.resetPassword = resetPassword;
        $scope.forbidLogin = forbidLogin;
        $scope.updateProfile = updateProfile;
    };
    
    am.controller('coachProfileCtrl', controllerObject);
});