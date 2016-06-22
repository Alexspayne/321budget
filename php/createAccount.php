<?php

include ('../php/functions.php');

$form = json_decode($_POST['account'], true);

$formUser = $formPass = $formName = $formEmail = $formPhoneNumber = null;

$formUser = validateFormData( $form['username'] );
$formPass = validateFormData( $form['password'] );
$formName = validateFormData( $form['fullname'] );
$formEmail = validateFormData( $form['email'] );
$formPhoneNumber = validateFormData( $form['phonenumber'] );



// connect to database
include ("../".getAppropriateConnectionBasedOnServer());

$formUserValidated = $formUser;
$formPassValidated = $formPass;
$formNameValidated = $formName;
$formPhoneNumberValidated = $formPhoneNumber;

if (!filter_var($formEmail, FILTER_VALIDATE_EMAIL)) {

 $formEmailValidated = "noemail@321budget.com";

}else{

 $formEmailValidated = $formEmail;

}

if(!$formPhoneNumberValidated)
{
 $formPhoneNumberValidated = "Phone number not provided";
}

$hashedPassword = password_hash($formPassValidated, PASSWORD_DEFAULT);

$query = "INSERT INTO users
		(userid, username, fullname, email, password, createddate, identifiers)
		VALUES (NULL,'$formUserValidated','$formNameValidated','$formEmailValidated','$hashedPassword',CURRENT_TIMESTAMP,'$formPhoneNumberValidated');";


if($formUserValidated &&
   $formPassValidated &&
   $formNameValidated &&
   $formEmailValidated){//if the form was filled out correctly
 $result = mysqli_query($conn, $query);
 
 if($result){
  echo "User created!";
  
  $_SESSION['loggedInUserName'] = $formNameValidated;
 }else{
  echo "Error updating record: " . mysqli_error($conn);
 }
 
}else{
 echo "Form data wasn't actually valid";
}


// close the mysql connection
mysqli_close($conn);


?>
