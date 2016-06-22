#!/usr/bin/php -q
<?php


include ('includes/functions.php');
////This code allows me to choose between 
include (getAppropriateConnectionBasedOnServer());



chdir(dirname(__FILE__));
$fd = fopen("php://stdin", "r");
$email = "";
while (!feof($fd)) {
    $email .= fread($fd, 1024);
}
fclose($fd);

//Logging the recieved email
$detail = $email;
eventLog($conn,$detail,"EMAIL");

if (strlen($email)<1) {
    die();
}

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
        if (preg_match("/^From: (.*) <(.*)>/", $lines[$i], $matches)) {
    //only saving the email so that we can easily reply.      
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

$array = grabParse($message, $conn);

if(addEntry($array, $from, $conn)){
    $detail = "The DataBase is updated from the PHP Script by " . $from;	
}else{
    $detail = "The DataBase is not updated. Attempt by ".$from." Error: " .mysqli_error($conn);
}
eventLog($conn,$detail);

mysqli_close($conn);


?>
