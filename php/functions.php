<?php 

function doIHaveFunctions(){
 
    return "you have functions!";
 
}

function addBudgetToDatabase($bName,$bDesc,$user,$conn){


    $query="";

}


function eventLog($conn,$detail="Something Happened",$type="DEBUG"){
 
    $queryLog = "INSERT INTO logs (`id`, `Type`, `Detail`, `Time`) VALUES (NULL, '$type', '$detail', CURRENT_TIMESTAMP)";
    return mysqli_query($conn, $queryLog);
 
}

function addEntryFromWebsite($debit,$credit,$reason,$user,$budgetid,$conn){

    $query = "INSERT INTO budgetentries 
(entryid, debit, credit, detail, timestamp, userid, budgetid)
VALUES (NULL,'$debit','$credit','$reason',CURRENT_TIMESTAMP,'$user','$budgetid');";
 
 
    if($result =  mysqli_query($conn, $query)){
        $type="DEBUG";
        $detail= "Added Entry to DataBase: " . $query;
    }else{
        $type="ERROR";
        $detail= "Query: " . $query .' Error: ' . mysqli_error($conn);
    }
    
    //Logging
    $queryLog = "INSERT INTO logs (`id`, `Type`, `Detail`, `Time`) VALUES (NULL, '$type', '$detail', CURRENT_TIMESTAMP)";
    mysqli_query($conn, $queryLog);
 
    return $result;
}

function addEntryEmail($entryArray,$conn){
        
    $debit = 0;
    $credit = 0;
    $reason = $entryArray["reason"];
    $budget = $entryArray["budget"];
    $userID = $entryArray["from"];

    //determine if it is a debit or a credit
    if(preg_match("/([Cc]redit)/",$entryArray["debitOrCredit"])){
        $credit = $entryArray["amount"];
    }else{
        $debit = $entryArray["amount"];
    }

    $query = "INSERT INTO budgetentries 
(entryid, debit, credit, detail, timestamp, userid, budgetid)
VALUES (NULL,'".$debit."','".$credit."','".$reason."',CURRENT_TIMESTAMP,'".$userID."','".$budget."');";
  
    if($result =  mysqli_query($conn, $query)){
        $type="DEBUG";
        $detail= "Added Entry to DataBase: " . $query;
        return "success";
    }else{
        $type="ERROR";
        $detail= "Query: " . $query .' Error: ' . mysqli_error($conn);
return "Query: " . $query .' Error: ' . mysqli_error($conn);
    }
    
    //Logging
    $queryLog = "INSERT INTO logs (`id`, `Type`, `Detail`, `Time`) VALUES (NULL, '$type', '$detail', CURRENT_TIMESTAMP)";
    mysqli_query($conn, $queryLog);


}

function grabParse($textToParse){

    $exploded = explode( chr( 1 ), str_replace( array(' ', '\n'), chr( 1 ), $textToParse ) );

    $gotAmount = false;

    $reason = "";  
    for ($i=0; $i < count($exploded); $i++) {

        if($gotAmount){

            //Capture the name of the budget
            if(preg_match("/#(.*)/",$exploded[$i],$matchArray)){
                $budgetName =  $matchArray[1];
            }else{
                //build a string for the reason

                $reason .= $exploded[$i] . " "; 
     
            }
        }
   
        if($i == 0)
        {
            if(preg_match("/([Cc]redit|[Dd]ebit)/", $exploded[$i], $matchArray)){
                $debitOrCredit = $matchArray[1];
            }
        }
   
        if(preg_match("/(?:\$|)(\d+\.\d+|\d+)/", $exploded[$i], $matchArray) && !$gotAmount){
            $amount = $matchArray[1];
            $gotAmount = true;
        }
    }

    $associativeArray =  array(
        "debitOrCredit" => $debitOrCredit,
        "amount" => $amount,
        "reason" => $reason,
        "budget" => $budgetName,
    );	
    return $associativeArray;
}


function getAppropriateConnectionBasedOnServer(){
    //This code allows me to choose between data base credentials based on the server
    if($_SERVER["HTTP_HOST"] == 'localhost:8888')
    {
        //	echo "Connected to LocalHost";
        return './php/creds/mamp.php';
    }else if($_SERVER["CONTEXT_DOCUMENT_ROOT"] == "/var/www/html"){
  
        return './php/creds/linuxsqlconnection.php';	    
  
    }else{
        //	echo "Connected to live site";
        return './php/creds/connection.php';		
    }
}

function validateFormData($formData) {
    $formData = trim( stripslashes( htmlspecialchars( strip_tags( str_replace( array( '(', ')' ), '', $formData ) ), ENT_QUOTES ) ) );
    return $formData;
}


function parseTextMatch($text,$regex){
 
    $output = preg_match($regex, $ext, $matches);
 
    return $matches;
}


function getUserIDFromEmailAddress($fromAddress,$conn){

    $fromAddress = trim($fromAddress);
    if(filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
        $from = $fromAddress;
    }else{
        $UserId = "999999999";//unknown user because didn't get an email
    }
  
    if($UserId != "999999999"){
        $userTableQuery = "
		SELECT *
		FROM users
		";
        $userTable = mysqli_query($conn, $userTableQuery);

        if($userTable){
            while($row = mysqli_fetch_assoc($userTable)){
                $phoneNumber = $row['identifiers'];
                if($from == $row["email"] || $from == $row["lastincomingaddress"] ||preg_match("/($phoneNumber)/", $from)){
                    $UserId = $row["userid"];
                }
            }
        }
    }

    return $UserId;
}

function getBudgetIDFromBudgetNameandUserID($budgetName, $userID, $conn){
    //First query the permissions and budget table
    //actually I could just add a condition to this query.

    $query = "select budgetpermissions.budgetid from
    budgetpermissions, budgets
    where
    budgetpermissions.userid = ".$userID."
    and
    budgets.budgetid = budgetpermissions.budgetid
	and
	budgets.budgetname = '".$budgetName."'
	and
	budgetpermissions.permissionslevel <= 1;";

    $results = mysqli_query($conn,$query);

    while($row = mysqli_fetch_assoc($results)){
        $budgetID = $row['budgetid'];
    }
    
    return $budgetID;
}

function explodeEmail($email){

 // handle email
 $lines = explode("\n", $email);

 // empty vars
 $from = "";
 $to="";
 $subject = "";
 $headers = "";
 $message = "";
 $splittingheaders = true;

 for ($i=0; $i < count($lines); $i++) {
  if ($splittingheaders) {
   // this is a header
   $headers .= $lines[$i]."\n";
   // look out for special headers
   if (preg_match("/^Subject: (.*)/", $lines[$i], $matches)) {
    $subject = $matches[1];
   }
   if (preg_match("/^From: (.*)/", $lines[$i], $matches)) {
    if(filter_var($matches[2], FILTER_VALIDATE_EMAIL)) {
     
     $from = $matches[2];
     
    }else{
     
     $from = $matches[1];
     
    }
   }
   if (preg_match("/^To: (.*)/", $lines[$i], $matches)) {
    $to = $matches[1];
   }
  } else {
   // not a header, but message
   $message .= $lines[$i]."\n";
  }
  if (trim($lines[$i])=="") {
   // empty line, header section has ended
   $splittingheaders = false;
  }
 }

 $array = array(
  "message" => $message,
  "from" => $from
 );
 return $array;
}

function updateAddress($userID,$address,$conn){ 
    $queryToAddAddress = "UPDATE users 
		SET lastincomingaddress = '".$from."'
		WHERE user = '".$userID."'";
    
    mysqli_query($conn, $queryToAddAddress);
}

?>