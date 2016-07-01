angular
    .module("ngBudget")
    .factory('budgetFactory', function($http) {

	$http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

function callDB(queryName,param1,param2){
	
	var req = {
	    method: 'POST',
	    url: '../../src/budget_mysql.php',
	    //   data: "id=" + 1
	    data: "queryName=" + queryName + "&" +
		"param1=" + param1 + "&" +
		"param2=" + param2
	};
	    
	    return $http(req);
	}

	return {
	    callDB: callDB
	};
    });
