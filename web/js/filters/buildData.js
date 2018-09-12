/* 
 * (C) 2015, 2016 EZ Sport Ltd.
 */
define(['../modules'], function (am) {
    var filterObject = function (trainingService) {
        
        var movementsMap;
        trainingService.getMovements(function (_movements, _movementsMap) {
            movementsMap = _movementsMap;
        });
        
        return function (dataRow) {
            var cols = movementsMap[dataRow.movement_id].format;
            angular.forEach(cols, function(col, key) {
                col.val = dataRow[key];
            });
            
            return cols;
        };
    };

    am.filter('buildData', filterObject); // end filter
});



