angular
    .module("ngBudget")
    .service('loginService', function($http) {
	$http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
	this.login = function(login){
	    var req = {
		method: 'POST',
		url: '../../src/loginscript.php',
		data: "login=" + login 		
	    };
	    return $http(req);
	};
    });
