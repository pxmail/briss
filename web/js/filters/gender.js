/* 
 * (C) 2015, 2016 EZ Sport Ltd.
 */
define(['../modules'], function (filters) {
    var filterObject = function () {
        return function (gender) {
            if(!gender) {
                return;
            }
            
            if(gender === 'M') {
                return '男';
            }
            
            if(gender === 'F') {
                return '女';
            }
            
            return '未知';
        };
    };

    filters.filter('gender', filterObject); // end filter
});



