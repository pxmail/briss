/* 
 * (C) 2015, 2016 EZ Sport Ltd.
 */
define(['../modules'], function (filters) {
    var filterObject = function () {
        return function (input, flag) {
            if(flag) {
                return undefined;
            }
            
            return input;
        };
    };

    filters.filter('empty', filterObject); // end filter
});



