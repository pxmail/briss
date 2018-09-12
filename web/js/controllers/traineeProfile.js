/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $state, $stateParams, $filter, $ionicHistory, $ionicPopup, traineeService, accountService, qiniuService, coachService) {

        // 如果是运动员详细页面进入此页面， 将不显示头像和返回按钮
        // 如果是从运动员列表页面进入，需显示运动员头像及返回按钮
        $scope.showSimple = $state.current.name === 'trainee.profile';
        // 性别
        $scope.genders = [{name: 'M', value: '男'}, {name: 'F', value: '女'}];
        // 运动等级
        $scope.grades = ['业余', '二级', '一级', '健将', '国际健将'];

        var form = $scope.form = traineeService.trainee;
        $scope.token = null; // 七牛访问令牌

        var traineeId;
        if ($stateParams.trainee_id) {
            traineeId = $stateParams.trainee_id;
        } else {
            traineeId = accountService.getMyUid();
        }

        coachService.listBase(function (resp) {
            $scope.bases = resp.bases;
        });

        traineeService.get(traineeId, function (resp) {
            if (resp.trainee.dob !== '0000-00-00') {
                resp.trainee.dob_front = new Date(Date.parse(resp.trainee.dob));
            }

            if (resp.trainee.dot !== '0000-00-00') {
                resp.trainee.dot_front = new Date(Date.parse(resp.trainee.dot));
            }

            resp.trainee.height = parseInt(resp.trainee.height);
            resp.trainee.weight = parseInt(resp.trainee.weight);
            form = $scope.form = traineeService.trainee = resp.trainee;
        });

        function save() {
            form.dob = $filter('date')(form.dob_front, 'yyyy-MM-dd');
            form.dot = $filter('date')(form.dot_front, 'yyyy-MM-dd');
            $scope.saving = true;
            traineeService.update(form, function (resp) {
                $scope.saving = false;
                if (resp.trainee_id) {
                    if (window.__terminal === 'coach') {
                    	$state.go('settings.trainees');
				    } else {
				    	$ionicHistory.goBack();
				    }
                }
            });
        }

        function getUploadToken(isPrivate) {
            var param;
            if (isPrivate) {
                param = {'private': 1};
            }
            accountService.qiniu.get(param).$promise.then(function (resp) {
                if (resp && resp.token) {
                    $scope.token = resp.token;
                    $scope.base_url = resp.base_url;
                }
            });
        }

        function uploadAvatar() {
            var el = angular.element($('#avatar'))[0];
            var progress = angular.element($('#trainee_avatar_progress'));
            qiniuService.upload(el, $scope.token, function (hash) {
                $scope.form.avatar = $scope.base_url + hash;
                $scope.$digest();
            }, progress);
        }

        function updateTrainee(form) {
            traineeService.update(form, function (resp) {
                if (resp.trainee_id) {
                    $ionicPopup.alert({
                        title: '操作成功',
                        template: '运动员' + form.name + '的个人信息修改成功'
                    });
                }
            });
        }

        $scope.save = save;
        $scope.getUploadToken = getUploadToken;
        $scope.uploadAvatar = uploadAvatar;
        $scope.updateTrainee = updateTrainee;
    };

    am.controller('traineeProfileCtrl', controllerObject);
});


