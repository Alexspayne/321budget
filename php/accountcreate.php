<?php
include ('functions.php');
session_start();

//handle alerts
if( isset( $_SESSION['alert'] ) ) {
 // new entry added
 if( $_SESSION['alert'] == 'success' ) {
  $alertMessage = '<div class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Congrats on creating your account, '. $_SESSION['loggedInUserName'] .'!<br>
	Click "Log In" and enter your username and password to visit the site. :)</div>';
  $_SESSION['accountCreated'] = 'true';
 }
 if( $_SESSION['alert'] == 'failure' ) {
  $alertMessage = '<div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please enter all the fields correctly.</div>';
 }
 if( $_SESSION['alert'] == 'failureEmail' ) {
  $alertMessage = '<div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please enter a valid email.</div>';
 }
 if( $_SESSION['alert'] == 'failureUserName' ) {
  $alertMessage = '<div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please enter a novel username with atleast '. $minimumCharactersUserName .' characters.</div>';
 }
 if( $_SESSION['alert'] == 'failurePassword' ) {
  $alertMessage = '<div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please enter a password with atleast '. $minimumCharactersPassword .' characters.</div>';
 }

 $_SESSION['alert'] = null; //clear the alert so that it doesn't show on reload

}

include('header.php');

?>

<?php echo $alertMessage;?>

<account-form></account-form>

<?php
include('footer.php');
?>
