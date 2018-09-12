/* 
 * (C) 2016 EZ Sport Ltd.
 * 
 * 数字选择
 */
define(['../modules'], function (directives) {
    var directiveObject = function ($interval, dateFilter) {
        // return the directive link function. (compile function not needed)
        return function (scope, element, attrs) {
            var format, // date format
                    stopTime; // so that we can cancel the time updates

            // used to update the UI
            function updateTime() {
                element.text(dateFilter(new Date(), format));
            }

            // watch the expression, and update the UI on change.
            scope.$watch(attrs.ezCurrentTime, function (value) {
                if (value) {
                    format = value;
                    updateTime();
                }
            });

            stopTime = $interval(updateTime, 1000);

            // listen on DOM destroy (removal) event, and cancel the next UI update
            // to prevent updating time after the DOM element was removed.
            element.on('$destroy', function () {
                $interval.cancel(stopTime);
            });
        };
    };

    directives.directive('ezCurrentTime', directiveObject);
});



