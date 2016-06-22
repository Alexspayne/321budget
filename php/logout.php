<?php
    
    // did the user's browser send a cookie for the session?
    if( isset( $_COOKIE[ session_name() ] ) ) {
        
        // empty the cookie
        setcookie( session_name(), '', time()-86400, '/' );
        
    }

// clear all session variables
session_unset();

    // destroy the session
    session_destroy();
include('header.php');


    echo "<p>You been logged out. Have a nice day. :)</p><br>";

    //print_r($_SESSION);

    echo "<p><a href='/login.php'>Click here to log back in</a></p>";

include('footer.php');
?>
