/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $state, $stateParams, $ionicPopup, $filter, $ionicScrollDelegate, $timeout, trainingService, evaluationService) {

        $scope.setTab = function (tabName, editable) {
            $scope.tab = tabName;
            // 每次切换到这个 tab 时，重新获取评估表内容，以免运动员填表结束训练之后不能及时更新到当前客户端，造成数据覆盖
            if (tabName === 'evaluation') {
                getEvaluation(editable);
            }
            $ionicScrollDelegate.resize();
        };

        $scope.setTab('data');

        // 获取训练状态评估
        function getEvaluation(editable) {

            evaluationService.get($stateParams.trainee_id, $stateParams.date, function (resp) {
                var form = resp.evaluation || {
                    trainee_id: $stateParams.trainee_id,
                    pain: [{}]
                };
                form.editable = editable;
                $scope.evalForm = form;
            });
        }

        getEvaluation();

        /**
         * 客户说历史数据要可以修改，但数据库要修改，增加记录动作时间的列
         * 
         * 当状态为查看历史数据时，加载历史数据内容
         */
// 老板说不给客户做了        
//        if ($state.is('trainee.history')) {
//            $scope.isHistory = true;
//            $scope.trainDate = $stateParams.date;
//            trainingService.listByTrainee($stateParams.trainee_id, $stateParams.date, function (resp) {
//                if (resp.trainings) {
//                    $scope.trainings = resp.trainings;
//
//                    $scope.$emit('updateTotalActions', $scope.trainings.length);
//
//                    angular.forEach($scope.trainings, function (training) {
//                        training.movement_name = movementsMap[training.movement_id].name;
//                    });
//                }
//            });
//        } else {
//            $scope.trainDate = $filter('date')(new Date(), 'yyyy-MM-dd');
//        }

        /**
         * 转换运动员的训练记录
         */
        var movementsMap;
        trainingService.getMovements(function (_movements, _movementsMap) {
            $scope.movements = _movements;
            movementsMap = _movementsMap;
            getTrainingData();
        });


        var trainings;
        trainings = $scope.trainings = [];

        var movementsPopup, dataPopup;

//        $scope.currentMovementId = 0;
        $scope.error = false;

        /**
         * 显示选择动作对话框
         * @returns {undefined}
         */
        function showMovementsForm() {

            movementsPopup = $ionicPopup.show({
                templateUrl: 'page/inc/movements.html',
                title: '选择动作',
                cssClass: 'standard-dialog',
                scope: $scope,
                buttons: [
                    {
                        text: '取消',
                        type: 'button-default'
                    }]
            });
        }

        /**
         * 把一个对象的键和值影射（复制）到另一个对象中
         * @param {type} from
         * @param {type} to
         * @returns {undefined}
         */
        function mapObject(from, to) {
            $.each(from, function (key, val) {
                if (val) {
                    if (key.indexOf('number') === 0 || key.indexOf('group_id') === 0) {
                        val = parseFloat(val);
                    }
                    to[key] = val;
                }
            });
        }



        var dataForm;

        dataForm = $scope.dataForm = {
            trainee_id: $stateParams.trainee_id
        };

        /**
         * 显示一个编辑内容对话框
         * @param {type} row
         * @returns {undefined}
         */
        function showEditForm(row) {
            resetForm();
            mapObject(row, dataForm);
            dataForm.movement_id = parseInt(dataForm.movement_id);
            showDataForm(dataForm.movement_id, dataForm.movement_name, row);
        }

        /**
         * 显示一个创建数据的对话框
         * @param {type} movementId
         * @param {type} movementName
         * @param {type} updateRow
         */
        function showDataForm(movementId, movementName, updateRow) {

            if (movementsPopup) {
                movementsPopup.close();
            }

            movementId = parseInt(movementId);

            if (!updateRow) {
                resetForm();
                dataForm.movement_id = parseInt(movementId);
            }

            // 如果是___新录入__ 103（10s冲刺训练）尝试自动获取组数
            delete $scope.magicFn;

            // 103：10秒冲刺训练需要魔术函数来计算总距离
            if (dataForm.movement_id === 103) {
                $scope.magicFn = magicFn_103;
            }

            // 如果是新录入数据（updateRow = undefined），并且数据项存在 group_id 项，自动计算group_id
            if (typeof updateRow === "undefined" && movementsMap[ movementId ].format.group_id) {
                var groupId = 0;

                $.each(trainings, function () {
                    if (parseInt(this.movement_id) === movementId && this.group_id > groupId) {

                        groupId = this.group_id;
                    }
                });

                dataForm.group_id = ++groupId;
            }


            dataForm.trainee_id = $stateParams.trainee_id;

            // 显示输入数据对话框
            dataPopup = $ionicPopup.show({
                template: '<div data-ez-data-form="dataForm" data-magic-fn="magicFn"></div><div class="check-error" data-ng-class="{show: error}">{{error}}</div>',
                title: movementName,
                cssClass: 'standard-dialog',
                scope: $scope,
                buttons: [
                    {
                        text: '取消',
                        type: 'button-default'
                    },
                    {
                        text: '保存',
                        type: 'button-positive',
                        onTap: function (e) {
                            e.preventDefault();

                            // 检查要提交的表单是否正确
                            var checkResult = trainingService.check(dataForm);
                            if (checkResult !== true) {
                                $scope.error = "数据行“" + checkResult + "”未填写或格式错误";

                                $timeout(function () {
                                    $scope.error = false;
                                }, 3000);

                                return;
                            }

                            // 如果正在保存数据，将不再继续，防止重复提交
                            dataPopup.close();

                            if (dataForm.id) {
                                trainingService.updateData(dataForm, saveDataCallback);
                            } else {
                                trainingService.createData(dataForm, saveDataCallback);
                            }
                        }
                    }
                ]
            });

            /**
             * 保存数据成功后的回调函数
             * @param {type} resp
             */
            function saveDataCallback(resp) {
                if (resp.id) {
                    if (updateRow) {
                        mapObject(dataForm, updateRow);
                        resetForm();
                        dataPopup.close();
                    } else {
                        var newData = angular.copy(dataForm);
                        newData.id = resp.id;
                        newData.training_time = $filter('date')(new Date(), 'HH:mm:ss');
                        newData.movement_name = movementName;
                        trainings.push(newData);
                        $scope.$emit('updateTotalActions', $scope.trainings.length);

                        // 如果不是 10秒冲刺训练，关闭对话框
                        if (movementId === 103) {
                            delete dataForm.id;
                            delete dataForm.number_1;
                            delete dataForm.number_2;
                            delete dataForm.number_3;
                        } else {
                            dataPopup.close();
                        }
                        // 滚动到页面最下方
                        $ionicScrollDelegate.resize();
                        $ionicScrollDelegate.scrollBottom(true);
                    }
                }
            }

        } // <- end showForm

        /**
         * 10s 快速冲刺训练魔法函数
         * 用于获取当前组编号的最大总距离
         * @returns {undefined}
         */
        function magicFn_103(group_id, training_id) {
            var max = 0;
            $.each(trainings, function () {
                // 获取 103动作的相同组号下的最大总距离
                if (this.movement_id == 103 && this.group_id == group_id) {
                    if (!training_id || this.id < training_id) {
                        max = parseInt(this.number_4);
                    } else {
                        return false;
                    }
                }
            });
            return max;
        }

        function resetForm() {
            delete dataForm;
            delete $scope.dataForm;
            dataForm = $scope.dataForm = {};
        }

        /**
         * 获取运动员当天训练记录
         */
        function getTrainingData() {
            var training_date = $filter('date')(new Date(), 'yyyy-MM-dd');

            trainingService.listByTrainee($stateParams.trainee_id, training_date, function (resp) {
                trainings = $scope.trainings = resp.trainings;
                $scope.$emit('updateTotalActions', $scope.trainings.length);

                angular.forEach(trainings, function (training) {
                    training.movement_name = movementsMap[training.movement_id].name;
                });

                $timeout(function () {
                    $ionicScrollDelegate.resize();
                }, 500);

            });
        }

        /**
         * 保存结束训练表单
         * @returns {undefined}
         */
        function saveEndTrain() {
            evaluationService.finish($scope.evalForm, function (resp) {
                if (resp.success) {
                    showInfo('保存成功');
                    $scope.$parent.trainee.last_status = '2';
                    $scope.evalForm.editable = false;
                }
            });
        }

        var beginSaving = false;

        function saveBeginTrain() {

            if (beginSaving) {
                return;
            }

            var checkResult = evaluationService.checkFormBefore($scope.evalForm);
            if (checkResult !== true) {
                $ionicPopup.alert({
                    title: '错误',
                    template: checkResult + '未填写'
                });
                return;
            } else {
                beginSaving = true;
                evaluationService.create($scope.evalForm, function () {
                    showInfo('训练前评估表保存成功');
                    $scope.evalForm.editable = false;
                    beginSaving = false;
                    $scope.setTab('data');
                });
            }
        }

        function setEndTrain() {
            $scope.evalForm.editable = true;
            // 重新获取一次评估表，防止运动员在手机端填写后没有反映到教练员端
            getEvaluation(true);
        }

        var infoPopup;
        function showInfo(info) {
            infoPopup = $ionicPopup.show({
                title: '成功',
                template: '<div class="popup-msg">' + info + '</div>',
                cssClass: 'info-popup',
                scope: $scope
            });

            $timeout(infoPopup.close, 2000);
        }

        $scope.previous = {
            data: [],
            cursor: 0,
            current: null
        };

        function showWarning(msg) {
            $('#data-warning-msg').html('<span>' + msg + '</span>');
            $timeout(function () {
                $('#data-warning-msg').html('');
            }, 3000);
        }

        function prevData(e) {
            $('#data-warning-msg').text('');
            e.preventDefault();
            if ($scope.previous.cursor > 0) {
                $scope.previous.cursor--;
                $scope.previous.current = $scope.previous.data[$scope.previous.cursor];
            } else {    // 偿试加载历史数据
                var row = $scope.previous.data[0];
                trainingService.getPrevious(row.id, row.trainee_id, row.movement_id).then(function (resp) {
                    if (resp.training && resp.training.id) {
                        $scope.previous.data.unshift(resp.training);
                        $scope.previous.current = resp.training;
                    } else {
                        showWarning('已是最早一条数据');
                    }
                });
            }
        }


        function nextData(e) {
            $('#data-warning-msg').html('');
            e.preventDefault();
            if ($scope.previous.cursor < $scope.previous.data - 1) {
                $scope.previous.cursor++;
                $scope.previous.current = $scope.previous.data[$scope.previous.cursor];
            } else {
                // 偿试获取之后的数据
                var row = $scope.previous.data[$scope.previous.cursor];
                trainingService.getSubsequent(row.id, row.trainee_id, row.movement_id).then(function (resp) {
                    if (resp.training && resp.training.id) {
                        $scope.previous.data.push(resp.training);
                        $scope.previous.cursor++;
                        $scope.previous.current = resp.training;
                    } else {
                        showWarning('已是最后一条数据');
                    }
                });
            }
        }


        function showHistory(row) {
            var previous = $scope.previous;
            previous.data.length = 0;
            previous.cursor = 0;
            previous.current = null;
            $scope.current = row;

            trainingService.getPrevious(row.id, row.trainee_id, row.movement_id).then(function (resp) {
                var popup;
                if (resp.training && resp.training.id) {
                    previous.data.push(resp.training);
                    previous.current = resp.training;

                    popup = $ionicPopup.show({
                        title: row.movement_name + ' - 历史数据',
                        template: '<div id="data-warning-msg"></div><div class="training-date">本次训练数据：</div><div data-ez-data="current"></div><div class="training-date" style="margin-top: 20px;">历史训练数据：{{previous.current.training_date}}</div><div data-ez-data="previous.current"></div>',
                        scope: $scope,
                        cssClass: 'dlg-history-training',
                        buttons: [{
                                text: '前一组',
                                onTap: prevData
                            },
                            {
                                text: '下一组',
                                onTap: nextData
                            },
                            {
                                text: '关闭'
                            }]
                    });


                } else {
                    popup = $ionicPopup.show({
                        title: '错误',
                        template: '找不到上一次的数据',
                        cssClass: 'info-popup',
                        scope: $scope
                    });

                    $timeout(popup.close, 2000);
                }

            });
        }

        $scope.saveEndTrain = saveEndTrain;
        $scope.showMovementsForm = showMovementsForm;
        $scope.showDataForm = showDataForm;
        $scope.showEditForm = showEditForm;
        $scope.saveBeginTrain = saveBeginTrain;
        $scope.setEndTrain = setEndTrain;
        $scope.showHistory = showHistory;
    };

    am.controller('coachTraineeHomeCtrl', controllerObject);
});


