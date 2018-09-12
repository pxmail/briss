/* 
 * (C) 2015, 2016 EZ Sport Ltd.
 */
define(['../modules'], function (filters) {
    var filterObject = function () {
        return function (role) {
        	switch(role) {
            	case "1":
            		return "触屏端用户";
            		break;
            	case "2":
            		return "管理员";
            		break;
            	case "3":
            		return "教练员";
            		break;
            	case "4":
            		return "运动员用户";
            		break;
        	}
        };
    };

    filters.filter('role', filterObject); // end filter
});