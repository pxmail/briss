/* 
 * (C) 2015, 2016 EZ Sport Ltd.
 */
define(['../modules'], function (filters) {
    var filterObject = function () {
        function resistance(val) {
            val = parseInt(val);
            if(val === 0) {
                return '无';
            } else if(val === 10) {
                return '中等';
            } else if (val === 20) {
                return '高';
            }
            
            return val;
        }
        
        return function (input, filter) {
            if(filter === 'int') {
                var out = parseInt(input);
                return isNaN(out) ? '-' : out;
            } else if(filter === 'float') {
                var out = parseFloat(input);
                return isNaN(out) ? '-' : out;
            } else if(filter === 'resistance') {
                return resistance(input);
            }
            
            return input;
        };
    };

    filters.filter('dataFilter', filterObject); // end filter
});



