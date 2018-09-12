/* 
 * (C) 2016 EZ Sport Ltd.
 * 
 * 数字选择
 */
define(['../modules'], function (directives) {
    var componentObject = {
        template: '<ul class="ez-number" data-ng-class="{\'readonly\': $ctrl.readonly}">\n\
                    <li data-ng-repeat="i in [] | range : $ctrl.begin : $ctrl.end" data-on-release="$ctrl.setValue(i)" data-ng-class="{\'selected\': $ctrl.val == i}">{{i}}</li>\n\
                  </ul>',
        bindings: {
            begin: '@',
            end: '@',
            readonly: '<',
            val: '='
        },
        controller: function () {
            this.setValue = function (val) {
                if (!this.readonly) {
                    this.val = val;
                }
            };
        }
    };

    directives.component('ezNumber', componentObject);
});

