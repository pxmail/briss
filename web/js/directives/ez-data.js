/* 
 * (C) 2016 EZ Sport Ltd.
 * 
 * 填写数据表单指令
 */
define(['../modules', 'jquery'], function (directives) {

    var directiveObject = function () {

        return {
            restrict: 'A',
            scope: {
                data: '<ezData'
            },
            template: '<ul><li data-ng-repeat="col in data | buildData">\n\
                    <span class="data-label">{{col.title}}</span>\n\
                    <span class="data-value">{{col.val | dataFilter : col.filter}}</span>\n\
                    <span class="data-unit">{{col.unit}}</span>\n\
                </li></ul>'
        };

    };

    directives.directive('ezData', directiveObject);

});



