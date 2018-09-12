define(['../modules', 'jquery'], function (services) {
    'use strict';

    var serviceObject = function ($resource) {
        function checkFormBefore(form) {

            if (!form.self_rating || form.self_rating < 1 || form.self_rating > 7) {
                return '自我状态评价';
            }

            if (!form.desire || form.desire < 1 || form.desire > 7) {
                return '训练欲望';
            }

            if (!form.sleep || form.sleep < 1 || form.sleep > 7) {
                return '睡眠质量';
            }

            if (!form.appetite || form.appetite < 1 || form.appetite > 7) {
                return '食欲';
            }

//            if (form.omega_wave < 1 || form.omega_wave > 7) {
//                return 'Omega Wave';
//            }

//            if (form.rpe_before < 6 || form.rpe_before > 20) {
//                return '训练前RPE';
//            }

//            if (form.morning_pulse < 40 || form.morning_pulse > 110) {
//                return '晨脉';
//            }

//            if (form.hrv_before < 0) {
//                return '训练前HRV';
//            }


            if (form.pain) {
                var pain = [];
                angular.forEach(form.pain, function (obj) {
                    if (obj.part && obj.grade) {
                        pain.push({part: obj.part, grade: obj.grade});
                    }
                });
                form.pain = pain;
            }

            return true;
        }

        function create(form, callback) {
            $resource('api/evaluation/create').save(form).$promise.then(callback);
        }

        function get(trainee_id, date, callback) {
            var param = {};
            if (trainee_id) {
                param.trainee_id = trainee_id;
            }
            if (date) {
                param.date = date;
            }

            $resource('api/evaluation/get_by_date').get(param).$promise.then(callback);
        }
        
        function finish(form, callback) {
        	$resource('api/evaluation/finish').save(form).$promise.then(callback);
        }
        
        function listByDateRange(trainee_id, date_start, date_end, callback) {
            var param = {
                trainee_id: trainee_id,
                date_start: date_start,
                date_end: date_end
            };
            
            $resource('api/evaluation/lists').get(param).$promise.then(callback);
        }

        return {
            checkFormBefore: checkFormBefore,
            create: create,
            get: get,
            finish: finish,
            listByDateRange: listByDateRange
        };
    };

    services.factory('evaluationService', serviceObject);
});
