/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $ionicHistory, traineeService, pageService) {
        var form = $scope.form = {};
        
        function save() {
            // 比较2次输入的密码是否一致
            if(form.new_password !== form.cfm_password) {
                pageService.info('两次输入的新密码不一致');
                return;
            }
            
            $scope.saving = true;
            traineeService.updatePassword(form, function(resp) {
                $scope.saving = false;
                if(resp.success) {
                    $ionicHistory.goBack();
                }
            });
        }
        
        $scope.save = save;
    };

    am.controller('traineePasswordCtrl', controllerObject);
});


