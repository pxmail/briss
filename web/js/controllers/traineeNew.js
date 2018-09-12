/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $ionicPopup, $filter, coachService, traineeService) {
    	//运动等级
    	$scope.grades = ['业余','二级','一级','健将','国际健将'];

        var form = $scope.form = traineeService.trainee = {};
        $scope.saving = false;
        function register() {
            $scope.saving = true;
            form.dob = $filter('date')(form.dob_front, 'yyyy-MM-dd');
            form.dot = $filter('date')(form.dot_front, 'yyyy-MM-dd');

            coachService.registerTrainee(form, function (resp) {
                $scope.saving = false;

                if (resp.trainee_id) {
                    var popup = $ionicPopup.alert({
                        title: '注册成功',
                        template: '运动员' + form.name + '已成功注册'
                    });

                    popup.then(function () {
                        form = $scope.form = {};
                    });
                }
            });
        }
        
        coachService.listBase(function (resp){
        	$scope.bases = resp.bases;
        });
        
        $scope.register = register;
    };

    am.controller('traineeNewCtrl', controllerObject);
});