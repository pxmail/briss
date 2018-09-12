/* 
 * (C) 2015, 2016 EZ Sport Ltd.
 */
define(['../modules'], function (am) {
    var filterObject = function () {
        return function (val) {
            if( 0 == val) {
                return '无阻力';
            } else if(10 == val) {
                return '中等阻力';
            } else if(20 == val) {
                return '高阻力';
            }
        };
    };

    am.filter('load', filterObject); // end filter
});



