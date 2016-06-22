<?php

include ('php/functions.php');

if( isset( $_POST['login'] ) ) {
    // create variables
    // wrap the data with our function
    $formUser = validateFormData( $_POST['username'] );
    $formPass = validateFormData( $_POST['password'] );
    // connect to database
    //    include('connection.php');
    include (getAppropriateConnectionBasedOnServer());
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
            header("Location: index.php");
            //		} else if($formPass == $hashedPass){//accepting unhashed to testing
            //			session_start();
            //
            //            // store data in SESSION variables
	    //            $_SESSION['loggedInUser'] = $user;
	    //            $_SESSION['loggedInEmail'] = $email;
	    //            header("Location: profile.php");
	    //        
        } else { // hashed password didn't verify
            
            // error message
            $loginError = "<div class='alert alert-danger'>Wrong username / password combination. Try again.</div>";
            
        }
        
    } else { // there are no results in database
        
        $loginError = "<div class='alert alert-danger'>No such user in database. Please try again. <a class='close' data-dismiss='alert'>&times;</a></div>";
        
    }
    
    // close the mysql connection
    mysqli_close($conn);
    
}
include('php/header.php');
?>
