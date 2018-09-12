/* 
 * (C) 2015, 2016 EZ Sport Ltd.
 */
define(['../modules'], function (am) {
    var filterObject = function () {
        return function (dob) {

            if (!dob || dob === '0000-00-00')
                return '-';

            var yearOfBirth = parseInt(dob.substring(0, 4));
            var year = (new Date()).getFullYear();

            var age = year - yearOfBirth;
            return age;
        };
    };

    am.filter('age', filterObject); // end filter
});



