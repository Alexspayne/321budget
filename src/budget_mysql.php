<?php

include("../src/data.php");
session_start();

if($_POST['queryName'] === 'getAll'){
 getAccessibleBudgets($_SESSION['loggedInUserId']);
}
else if($_POST['queryName'] === 'addBudget')
{
 addBudget($_SESSION['loggedInUserId'],$_POST['param1'],$_POST['param2']);
}
else if($_POST['queryName'] === 'addPermission')
{
 addPermission($_SESSION['loggedInUserId'],$_POST['param1'],$_POST['param2']);
}
else if($_POST['queryName'] === 'deleteEntry')
{
 deleteEntry($_SESSION['loggedInUserId'],$_POST['param1']);
}
else if($_POST['queryName'] === 'addEntry')
{
 addEntry($_SESSION['loggedInUserId'],$_POST['param1']);
}
else if($_POST['queryName'] === 'getLogs')
{
 getLogs($_SESSION['loggedInUserId']);
}

?>
