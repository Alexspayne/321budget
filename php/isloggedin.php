<?php
session_start();
if($_SESSION['loggedInUserId']){
 $SessionInfo = [
  "username" => $_SESSION['loggedInUserName'],
  "isLoggedIn" => true,
  ];

}else{
 $SessionInfo = [
  "username" => "nobody",
  "isLoggedIn" => false,
 ];
}

echo json_encode($SessionInfo);


