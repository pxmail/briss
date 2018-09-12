/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules', 'jquery'], function (am) {

    var controllerObject = function ($scope, $timeout, $state, $ionicPopup, traineeService, evaluationService, $filter) {
        // 页面加载时列出近期活跃运动员
        traineeService.listRecent(function (resp) {
            $scope.trainees = resp.trainees;
        });

        function query() {
            if ($scope.keyword && $scope.keyword.length > 0) {
                traineeService.query($scope.keyword, function (trainees) {
                    $scope.trainees = trainees;
                });
            }
        }

        var currentTrainee, popup;
        var form;
        form = $scope.form = {};
        $scope.saving = false;
        $scope.error = null;

        function resetForm() {
            form.self_rating = 0;
            form.desire = 0;
            form.sleep = 0;
            form.appetite = 0;
            form.omega_wave = 0;
            form.pain = [{}];
            form.rpe_before = 0;
            form.morning_pulse = null;
            form.hrv_before = null;
        }
        resetForm(); // controller 加载时对 form 实行一次初始化

        function showForm(trainee) {
            if (currentTrainee !== trainee) {
                resetForm();
                currentTrainee = trainee;
                form.trainee_id = trainee.id;
            }

            popup = $ionicPopup.show({
                template: '<div data-ez-evaluation="form" data-date="today"></div><div class="check-error" data-ng-class="{show: error}">{{error}}</div>',
                title: '训练前状态评估',
                cssClass: 'standard-dialog',
                scope: $scope,
                buttons: [
                    {
                        text: '取消',
                        type: 'button-default'
                    },
                    {
                        text: '开始训练',
                        type: 'button-positive',
                        onTap: function (e) {
                            e.preventDefault();
                            if ($scope.saving) {
                                return;
                            }

                            var checkResult = evaluationService.checkFormBefore(form);
                            if (checkResult !== true) {
                                $scope.error = checkResult + '未填写';
                                $timeout(function () {
                                    $scope.error = null;
                                }, 3000);

                                return;
                            }

                            $scope.saving = true;
                            evaluationService.create(form, function (resp) {
                                $scope.saving = false;
                                popup.close();
                                if (resp.success) {
                                    currentTrainee.last_train_date = $scope.today;
                                    currentTrainee.last_status = '1';
                                }
                            });
                        }
                    }
                ]
            });
        }

        function gotoTraineeCalendar(trainee_id) {
            $state.go('trainee.calendar', {trainee_id: trainee_id});
        }

        $scope.$on('$stateChangeStart', function () {
            if (popup)
                popup.close();
        });

        $scope.decide = function (trainee) {
            if (trainee.train_status === 'not-in') {
                showForm(trainee);
                return;
            }

            if (trainee.train_status === 'in') {
                $state.go('trainee.home', {trainee_id: trainee.id});
                return;
            }

            if (trainee.train_status === 'finished') {
                $state.go('trainee.calendar', {trainee_id: trainee.id});
                return;
            }
        };

        $scope.getHistory = function (event, trainee_id) {
            event.stopPropagation();
            $state.go('trainee.calendar', {trainee_id: trainee_id});
        };

        $scope.today = $filter('date')(new Date().valueOf(), 'yyyy-MM-dd');
        $scope.keyword = null;
        $scope.query = query;
        $scope.showForm = showForm;
        $scope.gotoTraineeCalendar = gotoTraineeCalendar;
    };
    am.controller('traineeQueryCtrl', controllerObject);
});


