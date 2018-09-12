/* 
 * (C) 2015, 2016 EZ Sport Ltd.
 */
define(['../modules'], function (filters) {
    var filterObject = function ($filter) {
        var today = $filter('date')(new Date().valueOf(), 'yyyy-MM-dd');
        return function (trainee) {
            if(trainee) {
                if(trainee.last_train_date === today) {
                    if(trainee.last_status === '1') {
                        trainee.train_status = 'in';
                        return '正在训练';
                    } else {
                        trainee.train_status = 'finished';
                        return '训练完成';
                    }
                } else {
                    trainee.train_status = 'not-in';
                    return '未入场';
                }
            }
        };
    };

    filters.filter('traineeStatus', filterObject); // end filter
});



