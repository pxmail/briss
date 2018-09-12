/* 
 * (C) 2016 EZ Sport Ltd.
 */

define(['../modules'], function (am) {

    var controllerObject = function ($scope, $state, $stateParams, $animate,
            trainingService, accountService) {

        var trainee_id;
        if ($stateParams.trainee_id) {
            trainee_id = $stateParams.trainee_id;
        } else {
            trainee_id = accountService.getMyUid();
        }

        trainingService.getCalendar(trainee_id, null, function (resp) {
            $scope.calendar = [];
            angular.forEach(resp.calendar, function (month) {
                $scope.calendar.push({'month': month});
            });
        });


        function loadDate(c) {

            if (!c.date) {
                c.loading = true;
                trainingService.getCalendar(trainee_id, c.month, function (resp) {
                    if (resp.calendar) {
                        c.date = resp.calendar;
                        delete c.loading;
                    }
                });

                c.hideDate = false;
            } else if (c.hideDate) {
                c.hideDate = false;
            } else if (c.hideDate === false) {
                c.hideDate = true;
            }
        }

        function history(date) {
            $state.go('trainee.history', {date: date});
        }

        function needShowYear(idx) {
            if ($scope.calendar) {
                if (idx === 0) {
                    return true;
                }
                return $scope.calendar[idx].month.substring(0, 4) !== $scope.calendar[idx - 1].month.substring(0, 4);
            }
        }

        $scope.loadDate = loadDate;
        $scope.history = history;
        $scope.needShowYear = needShowYear;
    };



    am.controller('traineeCalendarCtrl', controllerObject);
});


