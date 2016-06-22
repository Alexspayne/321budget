<?php
include('./php/loginscript.php');
?>
<div class="container">

 <div class="col-md-8">
  <h1>Login</h1>
  <p class="lead">Use this form to log in to your account</p>

  <?php echo $loginError; ?>

  <form class="form-inline " action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>" method="post">

   <div class="form-group">
    <label for="login-username" class="sr-only">Username</label>
    <input type="text" class="form-control" id="login-username" placeholder="username" name="username">
   </div>
   <div class="form-group">
    <label for="login-password" class="sr-only">Password</label>
    <input type="password" class="form-control" id="login-password" placeholder="password" name="password">
   </div>
   <button type="submit" class="btn btn-default" name="login">Login!</button>

  </form>
  <a href="./php/accountcreate.php">Create a new account</a>

 </div>
 <div class="col-md-4">
  <h1>Welcome to my project!</h1>
  <p>This website is a work in progress.</p>
  <p>Feel free to create an account to explore. If you'd prefer, you can log in with user name: demo and password: demo to access a dummy account to try out some features.</p>

  
 </div>
 <about-page class="col-md-12" ng-show="aboutToggle"></about-page>
</div>

<?php
include('./php/footer.php');
?>
