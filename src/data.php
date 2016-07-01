<?php

function getAccessibleBudgets($userForQuery){
 include ('../php/functions.php');
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
users.userid = budgetentries.userid
ORDER BY budgetentries.timestamp DESC;"; /* This query will be dynamic based on budgetId */

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


function addBudget($userIDunsafe,$budgetNameunsafe,$budgetDescriptionunsafe){

 include ('../src/functions.php');
 ////This code chooses the credentials and make a connection
 include ("../" . getAppropriateConnectionBasedOnServer());

 //validations
 $userID = validateFormData($userIDunsafe);
 $budgetName = validateFormData($budgetNameunsafe);
 $budgetDescription = validateFormData($budgetDescriptionunsafe);
 /* Create budget and add permissions*/


 $query = "INSERT INTO `alexhann_ledger`.`budgets` (`budgetid`, `budgetname`, `detail`, `createdtime`)
VALUES (NULL,'" .
	  $budgetName. "','" .
	  $budgetDescription. "',
 CURRENT_TIMESTAMP);";

 $query .= "INSERT INTO `alexhann_ledger`.`budgetpermissions`
(`permissionid`, `userid`, `budgetid`, `createdtime`, `permissionslevel`)
VALUES (NULL,
 '".$userID."',
 LAST_INSERT_ID(),
 CURRENT_TIMESTAMP, '0');
";

 $query .="SELECT budgetid FROM `budgetpermissions` WHERE permissionid = LAST_INSERT_ID();";
 
 $result = mysqli_multi_query($conn, $query);

 if($result){
  do
  {
   // Store first result set
   if ($result2=mysqli_store_result($conn)) {
    // Fetch one and one row
    while ($row=mysqli_fetch_row($result2))
    {
     echo $row[0];
    }
    // Free result set
    mysqli_free_result($result2);
   }
  }
  while (mysqli_next_result($conn));

 }
 else{
  echo 'failed to add budget. '. mysqli_error($conn);
 }
}

function deleteEntry($userIDunsafe,$entryIDunsafe){

 include ('../src/functions.php');
 ////This code chooses the credentials and make a connection
 include ("../" . getAppropriateConnectionBasedOnServer());

 //validations
 $userID = validateFormData($userIDunsafe);

 $entryID = validateFormData($entryIDunsafe);

 /* Remove entry from ledger */

 $query = "DELETE FROM `alexhann_ledger`.`budgetentries` WHERE `budgetentries`.`entryid` = '".$entryID."';";

 $result = mysqli_query($conn, $query);

 if($result){
  echo 'Entry deleted';
 }
 else{
  echo 'failed to delete entry. '. mysqli_error($conn);
 }
}

function addPermission($userID,$budgetID,$permissionLevel){

 include ('../src/functions.php');

 include ("../" . getAppropriateConnectionBasedOnServer());

 /* Create budget */
 $query = "INSERT INTO `alexhann_ledger`.`budgetpermissions` (`permissionid`, `userid`, `budgetid`, `createdtime`, `permissionslevel`)
 VALUES (NULL, '" .
	  $userID . "','" .
	  $budgetID . "'," .
	  "CURRENT_TIMESTAMP, '" .
	  $permissionLevel . "');" . ";";


 $result = mysqli_query($conn, $query);

 if($result){

  echo 'Permission added:' . print_r($result);
 }
 else{
  echo 'failed to add permission. '. mysqli_error($conn);
 }

}

function addEntry($user,$entryJSON){

 include ('../src/functions.php');
 ////This code chooses the credentials and make a connection
 include ("../" . getAppropriateConnectionBasedOnServer());

 $entryArray = json_decode($entryJSON, true);

 $debitOrCreditFromPost = $entryArray['type'];
 $entryFromPost = $entryArray['dollar'];
 $reasonFromPost = $entryArray['description'];
 $budgetidFromPost = $entryArray['budgetid'];

 $etype = validateFormData($debitOrCreditFromPost);

 if( $etype == "debit"){
  $debit = validateFormData( $entryFromPost);
 }else{
  $credit = validateFormData( $entryFromPost);
 }
 $reason = validateFormData( $reasonFromPost);
 $budgetid = validateFormData( $budgetidFromPost);

 if(($debit || $credit) && $user){
  $result = addEntryFromWebsite($debit,$credit,$reason,$user,$budgetid,$conn);
  if( $result ){
   $detail = "Added entry for: ". $user . " To: " . $budgetid;
   $type = "DEBUG";
   eventLog($conn,$detail,$type);
   echo $detail;
  }else{
   $detail = "Failed to add entry: " . mysqli_error($conn);
   $type = "DEBUG";
   eventLog($conn,$detail,$type);
  }
 }else{
  echo "Failed to add entry." ;
 }
}


function getLogs($userid){
 /*  if($userid == 0){*/
 if($userid == 1){
  include ('../src/functions.php');
  ////This code chooses the credentials and make a connection
  include ("../" . getAppropriateConnectionBasedOnServer());

  $query = "SELECT * FROM logs ORDER BY Time DESC LIMIT 0 , 15";
  $results = mysqli_query($conn, $query);

  /* Saving the SQL results to a PHP array
     so that I can convert it to JSON */
  $logs=array();

  while($row = mysqli_fetch_assoc($results)){

   $logs[] = $row;

  }

  mysqli_close($conn);

  echo json_encode($logs);

 }
}
