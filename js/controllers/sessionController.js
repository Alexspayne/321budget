angular
    .module('budget-session', []);

angular
    .module('budget-session')
    .factory('sessionS',function($http){

	function SessionService(){
	    var self = this;
	    self.isLoggedIn = false;

	    self.userName = "";
		// See if user is logged in.
		//I need this to run whenever logout/login happens.
	    self.getSession = function(){
		return $http({method:"GET", url:"../../php/isloggedin.php"})
		    .success(function(data){
			self.userName = data.username;
			self.isLoggedIn = data.isLoggedIn; //This is just for testing
		    })
		    .error(function(error){
			console.log(error);
		    });
	    };
	    self.getSession();
	}

	return new SessionService();
    });

angular
    .module('budget-session')
    .controller('LogoutController',function($http, sessionS){
	var controller = this;
	
	$http({method:"GET", url:"../../php/logout.php"})
	    .success(function(data){
		console.log("logged out");
		sessionS.getSession();
	    })
	    .error(function(error){
		console.log(error);
	    });
	
    });
