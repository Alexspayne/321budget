angular
    .module('ngBudget')
    .config(function($routeProvider){
	$routeProvider
	    .when('/login',{
		templateUrl: '../app/components/pages/login/index.html'
	    })
	    .when('/', {
		templateUrl: '../app/components/pages/budgetview/index.html'		
	    })
	    .when('/about', {
		templateUrl: '../app/components/pages/about/index.html'		
	    })
	    .when('/logout', {
		templateUrl: '../app/components/pages/logout/index.html',
		controller: 'LogoutController'
	    })
	    .when('/accountcreate', {
		templateUrl: '../app/components/pages/newaccount/index.html'		
	    })
	    .otherwise({ redirectTo: '/' });
    });
