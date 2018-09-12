/* 
 * (C) 2015, 2016 EZ Sport Ltd.
 */
define(['../modules'], function (am) {
    var filterObject = function () {
        return function (date) {
            if (date) {
                return parseInt(date.substring(5, 8));
            }
        };
    };

    am.filter('month', filterObject); // end filter
});



