/* 
 * (C) 2016 EZ Sport Ltd.
 * 
 * UI说要做一个炫酷的星星按件
 */
define(['../modules'], function (directives) {

    var directiveObject = function () {

        return {
            restrict: 'A',
            scope: {
                val: '=',
                readonly: '<',
                title: '<'
            },
            template: '<div class="ez-star-container" data-ng-class="{\'readonly\': readonly}">\n\
                            <div class="ez-star-mask"></div>\n\
                            <div class="ez-star-component" data-ng-class="{\'show-title\':title}">\n\
                                <div data-ng-repeat-start="i in [1,2,3,4,5,6,7]" class="ez-star" data-on-release="tapStar(i)"><img src="image/star.svg" alt="" width="32" height="32"></div>\n\
                                <div data-ng-repeat-end class="ez-star-padding"></div>\n\
                            </div>\n\
                        </div>',
            controller: function ($scope) {
                var ctrl = this;

                $scope.tapStar = function (index) {
                    if (!$scope.readonly) {
                        $scope.val = index;
                    }
                };

                $scope.$watch('val', function (newVal) {
                    newVal = parseInt(newVal);
                    if (isNaN(newVal)) {
                        newVal = 0;
                    }

                    var width = ((7 - newVal) / 7 * 100) + '%';
                    ctrl.maskElement.css({width: width});

                });

            },
            link: function (scope, elm, attrs, ctrl) {
                ctrl.maskElement = angular.element(angular.element(elm.children()[0]).children()[0]);

            }

        };

    };

    directives.directive('ezStar', directiveObject);

});



