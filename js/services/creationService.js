angular
    .module("ngBudget")
    .service('creationService', function($http) {

	$http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

	this.addAccount = function(accountArray){
	
	var req = {
	    method: 'POST',
	    url: '../../src/createAccount.php',
	    data: "account=" + accountArray 

	};
	    return $http(req);
	};


    });
