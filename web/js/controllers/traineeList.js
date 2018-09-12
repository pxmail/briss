/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $ionicPopup, $ionicActionSheet, $state, traineeService, coachService) {
        function query() {
            // 先列出我的学员，如果没有添加自己的学员，列出所有学员
            coachService.listMyTrainee(function (resp) {
                if (resp.trainees) {
                    $scope.trainees = coachService.myTrainees = resp.trainees;
                    if (resp.trainees.length === 0) {
                        traineeService.listAll(function (resp) {
                            $scope.trainees = resp.trainees;
                        });
                    }
                }
            });

        }

        query();


        traineeService.listPending(function (resp) {
            $scope.pendingTrainees = resp.trainees;
        });

        function traineeAudit(event, trainee, status) {
            event.preventDefault();
            event.stopPropagation();

            var op = status === '1' ? '通过' : '拒绝';

            $ionicPopup.show({
                template: '确定<span class="assertive">' + op + '</span> ' + trainee.name + ' 的注册申请吗？',
                title: '操作确认',
                scope: $scope,
                buttons: [
                    {text: '取消'},
                    {
                        text: '确定',
                        type: 'button-positive',
                        onTap: function (e) {
                            var param = {'trainee_id': trainee.id, 'status': status};
                            traineeService.traineeAudit(param, function (resp) {
                                if (resp.success) {
                                    trainee.status = status;
                                }
                            });
                        }
                    }
                ]
            });


        }

        $scope.traineeAudit = traineeAudit;

        $scope.settings = {
            myTraineeOnly: localStorage.getItem('settings.myTraineeOnly') === 'true'
        };

        $scope.setMyTraineeOnlyFlag = function () {
            localStorage.setItem('settings.myTraineeOnly', $scope.settings.myTraineeOnly);
        };

        $scope.showAction = function (trainee, index) {

            // Show the action sheet
            $ionicActionSheet.show({
                buttons: [
                    {text: '查看训练记录'},
                    {text: '重置密码'}
                ],
                destructiveText: '删除运动员',
                titleText: '选择对“' + trainee.name + '”的操作',
                cancelText: '取消',
                buttonClicked: function (index) {
                    if (index === 0) {
                        $state.go('trainee.calendar', {trainee_id: trainee.id});
                    } else if (index === 1) {
                        resetPassword(trainee.id);
                    }
                    return true;
                },
                destructiveButtonClicked: function () {
                    deleteTrainee(trainee.id, index);
                    return true;
                }
            });
        };

        function resetPassword(traineeId) {
            $ionicPopup.show({
                template: '确定将密码重置为"ezsport"吗？',
                title: '重置密码',
                scope: $scope,
                buttons: [
                    {text: '取消'},
                    {
                        text: '确定',
                        type: 'button-positive',
                        onTap: function (e) {
                            var param = {'trainee_id': traineeId};
                            coachService.resetTraineePassword(param);
                        }
                    }
                ]
            });
        }

        function deleteTrainee(traineeId, index) {
            var param = {'trainee_id': traineeId};
            var confirmPopup = $ionicPopup.confirm({
                title: '删除确认',
                template: '删除后不可恢复，请确认操作',
                okText: '确认',
                cancelText: '取消'
            });

            confirmPopup.then(function (res) {
                if (res) {
                    coachService.deleteTrainee(param, function (resp) {
                        if (resp.success) {
                            $scope.trainees.splice(index, 1);
                        } else {
                            $ionicPopup.alert({
                                title: '操作失败',
                                template: '运动员已参加训练，不能删除'
                            });
                        }
                    });
                }
            });
        }
    };

    am.controller('traineeListCtrl', controllerObject);
});