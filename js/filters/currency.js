angular
    .module('ngBudget')
    .filter('currencyNoZero',function(currencyFilter){
	
	return function (input){
		if(input == 0.00){
		    input = "";
		}else{
		    input = input.replace(/(?:\$|)(\d+)(\.\d{2}|)/,'$$$1$2');
		}
	    return input;
	};
    });
