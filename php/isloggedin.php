<?php
session_start();
if($_SESSION['loggedInUserId']){
 echo $_SESSION['loggedInUserName'];
}else{
 echo false;
}


