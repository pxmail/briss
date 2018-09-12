/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $state, $stateParams, $ionicPopup, coachService) {
        $scope.isEdit = false;

        if ($state.current.name === 'settings.edit_base') {
            $scope.isEdit = true;

            if (!coachService.bases) {
                $state.go('settings.base');
                return;
            }

            $.each(coachService.bases, function () {
                if (this.id === $stateParams.base_id) {
                    $scope.base = this;
                    return false;
                }
            });
        } else {
            $scope.base = {};
        }

        if ($state.current.name === 'settings.base') {
            coachService.listBase(function (resp) {
                $scope.bases = coachService.bases = resp.bases;

            });
        }

        $scope.save = function () {
            if ($scope.isEdit) {
                saveEdit();
            } else {
                saveCreate();
            }
        };

        function saveCreate() {
            coachService.createBase($scope.base, function (resp) {
                if (resp.base_id) {
                    $state.go('settings.base');
                }
            });
        }
        ;

        function saveEdit() {
            coachService.updateBase({base_id: $scope.base.id, base_name: $scope.base.name}, function (resp) {
                if (resp.base_id) {
                    $state.go('settings.base');
                }
            });
        }
        ;


        $scope.deleteBase = function (event) {
            event.stopPropagation();
            event.preventDefault();

            var confirmPopup = $ionicPopup.confirm({
                title: '删除确认',
                template: '删除后不可恢复，请确认操作',
                okText: '确认',
                cancelText: '取消'
            });

            confirmPopup.then(function (res) {
                if (res) {
                    coachService.deleteBase({base_id: $scope.base.id}, function (resp) {
                        if (resp.success) {
                            $state.go('settings.base');
                        }
                    });
                }
            });
        };

    };

    am.controller('baseCtrl', controllerObject);
});