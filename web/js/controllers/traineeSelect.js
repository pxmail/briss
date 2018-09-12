/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, traineeService, coachService) {

        var trainees = $scope.trainees = {};

        if (!coachService.myTrainees) {
            coachService.listMyTrainee(function (resp) {
                coachService.myTrainees = resp.trainees;

                loadAllTrainee();
            });
        } else {
            loadAllTrainee();
        }

        function loadAllTrainee() {
            traineeService.listAll(function (resp) {
                $.each(resp.trainees, function () {
                    var trainee = this;
                    
                    if (!trainees[trainee.sport_id]) {
                        trainees[trainee.sport_id] = {
                            id: trainee.sport_id,
                            sport: trainee.sport,
                            trainees: []
                        };
                    }

                    $.each(coachService.myTrainees, function() {
                        if(this.id === trainee.id) {
                            trainee.is_mine = true;
                            return false;
                        }
                    });

                    trainees[this.sport_id].trainees.push(trainee);

                });
            });
        }

        $scope.toggleMyTrainee = function (trainee) {
            // 添加为我的运动员
            if (trainee.is_mine) {
                coachService.linkTrainee(trainee.id);
                // 往 coachService.myTrainees 添加一条数据
                coachService.myTrainees.push(trainee);
            } else {    // 从我的运动员列表中移除
                coachService.unlinkTrainee(trainee.id);
                // 从 coachService.myTrainees 删除一条数据
                var removeIndex;
                $.each(coachService.myTrainees, function(index) {
                    if(this.id === trainee.id) {
                        removeIndex = index;
                        return false;
                    }
                });
                
                coachService.myTrainees.splice(removeIndex, 1);
            }
        };

    };

    am.controller('traineeSelectCtrl', controllerObject);
});