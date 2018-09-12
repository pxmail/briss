/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $state, $filter, $ionicHistory, $ionicPopup, traineeService, coachService) {
        var form = $scope.form = traineeService.trainee;
        $scope.saving = false;
    	//运动等级
    	$scope.grades = ['业余','二级','一级','健将','国际健将'];
        function register() {
            $scope.saving = true;
            form.dob = $filter('date')(form.dob_front, 'yyyy-MM-dd');
            form.dot = $filter('date')(form.dot_front, 'yyyy-MM-dd');

            traineeService.register(form, function (resp) {
                $scope.saving = false;

                if (resp.success) {
                    var popup = $ionicPopup.alert({
                        title: '注册成功',
                        template: '请等待教练员审核'
                    });

                    popup.then(function () {
                        $state.go('login');
                    });
                }
            });
        }

        $scope.goBack = function () {
            $ionicHistory.goBack();
        };
        
        coachService.listBase(function (resp){
        	$scope.bases = resp.bases;
        });

        $scope.register = register;

    };

    am.controller('traineeRegisterCtrl', controllerObject);
});


