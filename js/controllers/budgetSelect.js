angular
    .module('ngBudget')
    .controller('BudgetSelectCtrl', function($scope) {

	this.canEdit = function(permission) {
     
	    return permission <=1;
	};

    });
