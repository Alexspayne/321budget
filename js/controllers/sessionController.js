angular
    .module('budget-session', []);

angular
    .module('budget-session')
    .controller('session',function($http){
	var controller = this;

	// See if user is logged in.

	$http({method:"GET", url:"../../php/isloggedin.php"})
	    .success(function(data){
		controller.isLoggedIn = data;

	    })
	    .error(function(error){
		console.log(error);
	    });
	
    });
		    
