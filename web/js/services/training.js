define(['../modules', 'jquery'], function (services) {
    'use strict';

    var serviceObject = function ($resource, $http) {
        function getCalendar(trainee_id, year_month, callback) {
            $resource('api/training/get_calendar').get({
                trainee_id: trainee_id,
                year_month: year_month
            }).$promise.then(callback);
        }

        function listByTrainee(trainee_id, training_date, callback) {
            $resource('api/training/list_by_trainee').get({
                trainee_id: trainee_id,
                training_date: training_date
            }).$promise.then(callback);
        }

        var movements, movementsMap = {};


        function getMovements(callback) {
            if (!movements) {
                $http.get('config/movements.json').then(function (resp) {
                    movements = resp.data;

                    $.each(movements, function () {
                        $.each(this, function () {
                            movementsMap[this.id] = this;
                        });
                    });

                    callback(movements, movementsMap);
                });
            } else {
                callback(movements, movementsMap);
            }

        }

        function createData(form, callback) {
//            formatForServer(form);
            $resource('api/training/create_data').save(form).$promise.then(callback);
        }

        function updateData(form, callback) {
//            formatForServer(form);
            $resource('api/training/update_data').save(form).$promise.then(callback);
        }

        function check(row) {
            // 检查有无必须输入的项目
            var result = true;
            $.each(movementsMap[row.movement_id].format, function (field_name, config) {
                if (config.allowEmpty) {
                    if (!row[field_name]) {
                        row[field_name] = 0;
                    }
                    return true;
                }
                if (typeof row[field_name] === 'undefined') {
                    result = config.title;
                    return false;
                }
            });


            return result;
        }

        function getMovement(id) {
            return movementsMap[id];
        }

        function getData(trainee_id, movement_id, start_date, end_date, callback) {
            var param = {
                trainee_id: trainee_id,
                movement_id: movement_id,
                start_date: start_date,
                end_date: end_date
            };

            $resource('api/training/get_data').get(param).$promise.then(callback);
        }

        function getPrevious(id, trainee_id, movement_id) {
            return $resource('api/training/get_previous').get({
                id: id,
                trainee_id: trainee_id,
                movement_id: movement_id
            }).$promise;
        }

        function getSubsequent(id, trainee_id, movement_id) {
            return $resource('api/training/get_subsequent').get({
                id: id,
                trainee_id: trainee_id,
                movement_id: movement_id
            }).$promise;
        }

        return {
            getCalendar: getCalendar,
            listByTrainee: listByTrainee,
            getMovements: getMovements,
            createData: createData,
            updateData: updateData,
            check: check,
            getMovement: getMovement,
            getData: getData,
            getPrevious: getPrevious,
            getSubsequent: getSubsequent
        };
    };

    services.factory('trainingService', serviceObject);
});
