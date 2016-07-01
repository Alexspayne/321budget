<?php

function getAccessibleBudgets($userForQuery){
    include ('../src/functions.php');
    ////This code chooses the credentials and make a connection
    include ("../" . getAppropriateConnectionBasedOnServer());
    /* Here I'm attempting to use the new database structure with budgets as a table */


	$test1 = "CREATE TEMPORARY TABLE budgetView_t1
AS
(
    select userid from
    users
    WHERE userid='$userForQuery'
);";
	$test2 = "CREATE TEMPORARY TABLE budgetView_t2
AS
(
/* The returns all the budgets with permissions.
We will use this as a starting point for the budget selection. */
    select budgetpermissions.budgetid, permissionslevel, budgets.budgetname from
    budgetpermissions, budgetView_t1, budgets
    where
    budgetpermissions.userid = budgetView_t1.userid
    and
    budgets.budgetid = budgetpermissions.budgetid
);";
	$test3 = "CREATE TEMPORARY TABLE budgetView_t3
AS
(
    select
budgets.budgetid,
budgets.detail,
budgets.budgetname,
budgetView_t2.permissionslevel
from
    budgetView_t2, budgets
    where
    budgets.budgetid = budgetView_t2.budgetid
    AND
    budgetView_t2.permissionslevel <=2
);";

	mysqli_query($conn, $test1);
	mysqli_query($conn, $test2);
	mysqli_query($conn, $test3);

	/* Getting allowed BudgtedIds */
	$QueryB = "SELECT * FROM budgetView_t3;";
	$resultsB = mysqli_query($conn, $QueryB);

	/* Saving the SQL results to a PHP array
	   so that I can convert it to JSON */
	$arrayForConvertB=array();
	while($ebudgeB = mysqli_fetch_assoc($resultsB)){

	    $arrayForConvertB[] = $ebudgeB;

	}
 
    /* Getting the budget from the budgetIds */
    /* This needs to be looped over the budgetIds array */
    /* foreach($budgetid in $budgetIds)*/
    /* Instead if iterating over the PHP array, I'm going to just
       nest this in the original iteration. */
    $arrayForConvert = array();

    foreach($arrayForConvertB as $budgetid)
    {
	$results = "";
	$query = "SELECT budgetentries.* , users.fullname
FROM budgetentries, users
	WHERE
	budgetid = '". $budgetid["budgetid"] ."'
AND
users.userid = budgetentries.userid;"; /* This query will be dynamic based on budgetId */

	$results = mysqli_query($conn, $query);

	/* Saving the SQL results to a PHP array
	   so that I can convert it to JSON */
	$budgets=array();
    $balance = 0;
	while($row = mysqli_fetch_assoc($results)){
        // This is the budget entries
        //Calculating balance
        $balance += $row['credit'];
        $balance -= $row['debit'];

	    $budgets[] = $row;
        
	}

	$individualBudgetObject =
	array("Budget Name" => $budgetid["budgetname"],
	      "Detail" => $budgetid["detail"],
	      "Budget ID" => $budgetid["budgetid"],
	      "Permissions" => $budgetid["permissionslevel"],
          "Balance" => $balance,
	      "Entries" => $budgets);
	/* Build array from results to convert to json */
	$arrayForConvert[] = $individualBudgetObject;
    }
    /* End loop over budgetID array */
    $ej3 = json_encode($arrayForConvert);

    mysqli_close($conn);

    echo $ej3;
    /*     echo $outB;*/
}

function getSum($budgetId){

    include ('../includes/functions.php');

    ////This code chooses the credentials and make a connection
    include ("../" . getAppropriateConnectionBasedOnServer());
        
    $query = "SELECT debit,credit FROM budgetentries WHERE budgetid = '" . $budgetId ."';";

    $result = mysqli_query($conn, $query);

    $testR = array();
    while($row = mysqli_fetch_assoc($result)){

	$balance += $row['credit'];
	$balance -= $row['debit'];
	$testR[] = $row['debit'];
    }
    $testR2[] = $balance;
    //endtemp
    
    $balance2 = json_encode($testR);
    $balance3 = json_encode($testR2);

    echo $balance3;

}
