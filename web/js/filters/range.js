/* 
 * (C) 2015, 2016 EZ Sport Ltd.
 */
define(['../modules'], function (filters) {
    var filterObject = function () {
        return function (input, begin, end) {
            begin = parseInt(begin);
            end = parseInt(end);

            if (begin < end) {
                for (var i = begin; i <= end; i++) {
                    input.push(i);
                }
            } else {
                for (var i = begin; i >= end; i--) {
                    input.push(i);
                }
            }
            return input;
        };
    };

    filters.filter('range', filterObject); // end filter
});



