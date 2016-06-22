
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
    //	doIHaveFunctions();
    //Logging
    $queryLog = "INSERT INTO logs (`id`, `Type`, `Detail`, `Time`) VALUES (NULL, '$type', '$detail', CURRENT_TIMESTAMP)";
    mysqli_query($conn, $queryLog);
    
    return $result;
}

function addEntryEmail($entryArray,$user,$conn){
	
    $debit = 0;
    $credit = 0;
    $reason = $entryArray["reason"];
    
    //Determine who the entry is from
    
    if(filter_var($user, FILTER_VALIDATE_EMAIL)) {
	$from = $user;
    }else{
	$from = "999999999";//unknown user because didn't get an email
    }
    if($from != "999999999"){
	$userTableQuery = "
		SELECT *
		FROM users
		";
	if($userTable = mysqli_query($conn, $query)){
	    while(!isset($confirmedUserId)){
		if($row = mysqli_fetch_assoc($userTable)){
		    $phoneNumber = $row['identifiers'];
		    if($from == $row["email"] || $from == $row["lastincomingaddress"] ||preg_match("/($phoneNumber)/", $from)){
			$confirmedUserId = $row["userid"];
		    }
		}
	    }
	}else{
	    $detail = "failed to get usertable during email script";
	    $type = "ERROR";
	    $queryLog = "INSERT INTO logs (`id`, `Type`, `Detail`, `Time`) VALUES (NULL, '$type', '$detail', CURRENT_TIMESTAMP)";
	    mysqli_query($conn, $queryLog);			
		}
	$detail = "email recieved but unable to parse email. From: " . $user;
	$type = "ERROR";
	$queryLog = "INSERT INTO logs (`id`, `Type`, `Detail`, `Time`) VALUES (NULL, '$type', '$detail', CURRENT_TIMESTAMP)";
	mysqli_query($conn, $queryLog);	
	}
    //Here I will put a lookup in the database for a particular user's from addresses.
    //but for now, I'll just hard code Hannah's and mine.
    
    $queryToAddAddress = "UPDATE users 
		SET lastincomingaddress = '$from'
		WHERE user = '$confirmedUserId'";

    mysqli_query($conn, $queryToAddAddress);
    //determine if it is a debit or a credit
    if(preg_match("/([Cc]redit)/",$entryArray["debitOrCredit"])){
	$credit = $entryArray["amount"];
    }else{
	$debit = $entryArray["amount"];
    }



    if($confirmedUserId == 1)
    {
	$budgetid = 0;
    }
    else if ($confirmedUserId == 2)
    {
	$budgetid = 1;
    }

    
    $query = "INSERT INTO budgetentries 
(entryid, debit, credit, detail, timestamp, userid, budgetid)
VALUES (NULL,'$debit','$credit','$reason',CURRENT_TIMESTAMP,'$confirmedUserId', '$budgetid');";
    
    return  mysqli_query($conn, $query);
    
}
function grabParse($textToParse, $conn){

    
    $regex = '/(?:([Cc]redit|[Dd]ebit|)(?: |\n)*)(?:\$|)(\d+\.\d+|\d+)(?: |\n)*(?:((?:\w+(?: |)(?:.*|))+|\w+)|)/';
    if(preg_match($regex,$textToParse,$matches)){
	$associativeArray =  array(
	    "debitOrCredit" => $matches[1],
	    "amount" => $matches[2],
	    "reason" => $matches[3],
	);	
    }
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


function getBalance($ledgerForBalance){
    $balance = 0;
    $totalCredits = 0;
    $totalDebits = 0;
    
    while($row = mysqli_fetch_assoc($ledgerForBalance)){
	$totalCredits += $row["credit"];
		$totalDebits += $row["debit"];	
    }
    
    $balance = $totalCredits - $totalDebits;
    
    return $balance;
}	

function parseTextMatch($text,$regex){
    
    $output = preg_match($regex, $ext, $matches);
    
    return $matches;
}

?>
