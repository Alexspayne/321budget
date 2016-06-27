#!/usr/bin/php -q
<?php
include ('../php/functions.php');
////This code allows me to choose between 
include ("../".getAppropriateConnectionBasedOnServer());

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


$array = explodeEmail($email);

$entryArray = grabParse($message);

 $entryArray['from'] = getUserIDFromEmailAddress($array['from'],$conn);
 $entryArray['budget'] = getBudgetIDFromBudgetNameandUserID($entryArray['budget'], $entryArray['from'],$conn);

addEntryEmail($entryArray, $conn);

mysqli_close($conn);
?>
