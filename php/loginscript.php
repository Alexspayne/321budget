<?php

include ('../php/functions.php');

$form = json_decode($_POST['login'], true);

$formUser = $formPass = null;

$formUser = validateFormData( $form['username'] );
$formPass = validateFormData( $form['password'] );

    // connect to database
    //    include('connection.php');
    include ("../".getAppropriateConnectionBasedOnServer());
    // create SQL query
    $query = "SELECT username, userid, fullname, email, password FROM users WHERE username='$formUser'";
    // store the result
    $result = mysqli_query( $conn, $query );
    // verify if result is returned
    if( mysqli_num_rows($result) > 0 ) {
        // store basic user data in variables
        while( $row = mysqli_fetch_assoc($result) ) {
            $user       = $row['username'];
            $email      = $row['email'];
            $hashedPass = $row['password'];
            $userid       = $row['userid'];
            $username = $row['fullname'];
        }
        // verify hashed password with the typed password
        if( password_verify( $formPass, $hashedPass ) ) {
            // correct login details!
            // start the session
            session_start();
            // store data in SESSION variables
            $_SESSION['loggedInUser'] = $user;
            $_SESSION['loggedInUserName'] = $username;
            $_SESSION['loggedInUserId'] = $userid;
            $_SESSION['loggedInEmail'] = $email;
	 echo "You've successfully logged in!";

        } else { // hashed password didn't verify
            
            // error message
           echo "You've entered an incorrect password.";
        }
        
    } else { // there are no results in database
        echo "No such user in database. Sorry!";
        
    }
     
    // close the mysql connection
    mysqli_close($conn);
   



