<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>321Budget.com</title>
  <link rel="icon" type="image/png" href="favicon.ico">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <!--Theme CSS-->
  <link rel="stylesheet" href="../styles/themes.css">
  <link rel="stylesheet" href="../styles/custom.css">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
   <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
   <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
 </head>


 <body style="padding-top: 60px;" ng-app="ngBudget" ng-controller="BudgetController as ledger">
  <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">

   <div class="container-fluid">

    <div class="navbar-header">
     <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
     </button>
     <?php
     if( $_SESSION['loggedInUser']) { // if user is logged in
     ?>
      <a class="navbar-brand" href="/index.php">321<strong>BUDGET</strong></a>
     <?php
     } else {//if not logged in
     ?>
      <a class="navbar-brand" href="/login.php">321<strong>BUDGET</strong></a>
     <?php
     }
     ?>

    </div>

    <div class="collapse navbar-collapse" id="navbar-collapse">

     <?php
     if( $_SESSION['loggedInUser']) { // if user is logged in
     ?>
      <ul class="nav navbar-nav">
       <li><a href="/index.php">Home</a></li>

       <li>	<a ng-click="aboutToggle = !aboutToggle">{{aboutToggle ? 'Hide ' : 'Show '}} About Page</a>
       </li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
       <p class="navbar-text">Logged in as <?php echo $_SESSION['loggedInUserName']; ?></p>

       <li><a href="/php/logout.php">Log out</a></li>
      </ul>
     <?php
     } else {//if not logged in
     ?>
      <ul class="nav navbar-nav">
      <li>	<a ng-click="aboutToggle = !aboutToggle">{{aboutToggle ? 'Hide ' : 'Show '}} About Page</a>
      </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
       <li><a href="/login.php">Log in</a></li>
      </ul>
     <?php
     }
     ?>
    </div>

   </div>

  </nav>

  <div class="container">
