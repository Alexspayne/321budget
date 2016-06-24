angular
    .module('budget-session', []);

angular
    .module('budget-session')
    .service('session',function($http){
	var service = this;

	// See if user is logged in.
	//I need this to run whenever logout/login happens.
	this.getSession = function(){
	$http({method:"GET", url:"../../php/isloggedin.php"})
	    .success(function(data){
		service.isLoggedIn = data;
	    })
	    .error(function(error){
		console.log(error);
	    });
	};
    });

angular
    .module('budget-session')
    .controller('sessionCtrl',function(session){
	var service = this;

	// See if user is logged in.
	//I need this to run whenever logout/login happens.


	
    });
		    
angular
    .module('budget-session')
    .controller('LogoutController',function($http, session){
	var controller = this;
	
	$http({method:"GET", url:"../../php/logout.php"})
	    .success(function(data){
		console.log("logged out");
		session.getSession();
	    })
	    .error(function(error){
		console.log(error);
	    });
	
    });
