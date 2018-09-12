/* 
 * (C) 2016 EZ Sport Ltd.
 */
define(['../modules'], function (am) {
    
    var controllerObject = function($scope, $state, coachService, pageService) {
        var form = $scope.form = {};
        
        function save() {
            // 比较2次输入的密码是否一致
            if(form.new_password !== form.cfm_password) {
                pageService.info('两次输入的新密码不一致');
                return;
            }
            
            coachService.updatePassword(form, function(resp) {
            	if (resp.success) {
            		pageService.info('密码修改成功');
            		$scope.form = {};
            	}
            });
        }
        
        $scope.save = save;
    };
    
    am.controller('coachPasswordCtrl', controllerObject);
});


