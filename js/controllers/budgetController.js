angular
    .module('ngBudget')
    .controller('BudgetController', function($scope, budgetFactory, sessionS) {
	/*
	 I'll want to save the budget table to a javascript object.
	 */

	$scope.SessionService = sessionS;
	
	$scope.deleteEntry = function (x){
	    budgetFactory.callDB('deleteEntry',x.entryid).success(function(data){
		$scope.getAll();		
	    }).error(function(error) {
		console.log(error);
	    });
	};

	$scope.getAll = function(){
	    budgetFactory.callDB('getAll').success(function(data) {
		$scope.budgets = data;
	    }).error(function(error) {
		console.log(error);
	    });
	};
    });

angular
    .module('ngBudget')
    .controller('LoginController',function($scope, $location, loginService, sessionS) {
	var loginCtrl = this;
	loginCtrl.alertToggle = true;
	this.loginInfo = {};

	this.login = function(){
	    this.alertToggle = true;
	    loginService.login(JSON.stringify(this.loginInfo))
		.success(function(data) {
		    console.log(data);
		    sessionS.getSession().then(function(){
			if(sessionS.isLoggedIn){
			    $location.path('/');
			}
		    });
		})
		.error(function(error) {
		    console.log(error);
		});
	};		
    });

angular
    .module('ngBudget')
    .controller('RedirectNonLogged',function($location, sessionS){
	if(!sessionS.isLoggedIn){
	    $location.path('/login');
	}
    });

angular
    .module('ngBudget')
    .directive('entryForm', function(){
	return {
	    restrict:'E',
	    templateUrl:'../../app/components/form/entryForm.html',
	    controller:function($scope,budgetFactory) {
		this.entry = {};
		this.entry.type = 'debit';
		//ng-init="$first && (select.canEdit(budget.Permissions)) && budget['Budget ID']" 
		
		this.addEntry = function (){
		    budgetFactory.callDB('addEntry',JSON.stringify(this.entry)).success(function(data){
			$scope.getAll();
		    }).error(function(error) {
			console.log(error);
		    });

		    this.entry = {type: this.entry.type,
				  budgetid : this.entry.budgetid};
		};

		this.onSubmit = function (form){
		    console.log("clicked on");
		    if(form.$invalid){
			//if form is not valid.
			console.log("invalid Form: ");
			console.log(form.budgetid);
		    }else{
			this.entry.dollar = this.entry.dollar.replace(/(?:\$|)(\d+)(\.\d{2}|)/,'$1$2');
			this.addEntry();
		    }
		};
	    },
	    controllerAs:'eform'
	};
    });

angular
    .module('ngBudget')
    .directive('budgetTable', function(){

	return {
	    restrict:'E',
	    templateUrl:'../../app/components/ledger/budgetTable.html'
	};
    });

angular
    .module('ngBudget')
    .directive('budgetForm', function(){
	// This allows me to display the budget creation form and add to the database.
	return {
	    restrict:'E',
	    templateUrl:'../../app/components/form/budgetForm.html',
	    controller:function($scope,budgetFactory) {
		this.budget = {};
		
		this.addBudget = function(){
		    budgetFactory.callDB('addBudget',this.budget.name,this.budget.description)
			.success(function(data) {
			    $scope.getAll();
			}).error(function(error) {
			    console.log(error);
			});
		    
		};
		
	    },
	    controllerAs:'bform'
	};
    });

angular
    .module('ngBudget')
    .directive('accountForm', function(){
	// This allows me to display the budget creation form and add to the database.
	return {
	    restrict:'E',
	    templateUrl:'../../app/components/form/accountForm.html',
	    controller:function(creationService) {
		this.account = {};
		
		this.addAccount = function(){
		    creationService.addAccount(JSON.stringify(this.account))
			.success(function(data) {

			})
			.error(function(error) {
			    console.log(error);
			});
		    // console.log(this.account);
		    this.account = {status:true};
		};
		
	    },
	    controllerAs:'aform'
	};
    });

angular
    .module('ngBudget')
    .directive('logTable', function() {

	return {

	    restrict:'E',
	    templateUrl:'../../app/components/ledger/logTable.html',
	    controller:function($scope,budgetFactory) {
		$scope.logtable = {};
		
		this.getLogs = function(){
		    budgetFactory.callDB('getLogs')
			.success(function(data) {
			    $scope.logtable = data;			   
			}).error(function(error) {
			    console.log(error);
			});			
		};		    
	    },
	    controllerAs:'logs'  
	};
	
    });


angular
    .module('ngBudget')
    .directive('aboutPage', function() {

	return {
	    restrict :'E',
	    templateUrl:'../../app/components/about/about.html'
	};   
    });


angular
    .module('ngBudget')
    .directive('navBar', function() {

	return {
	    restrict :'E',
	    templateUrl:'../../app/shared/navbar.html'
	};   
    });

