<?php
session_start();
if( !$_SESSION['loggedInUser']) { // if user is not logged in
 header("Location: login.php");
}
/* I will want to migrate the login to a view before I change this. */
?>

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
      <a class="navbar-brand">321<strong>BUDGET</strong></a>
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
       <li><a>Home</a></li>

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
  <about-page class="col-xs-12" id="mainA" ng-show="aboutToggle"></about-page>
  
  <div class="container">
   <div ng-view></div>
   
   <entry-form></entry-form>

   
   <div class="container col-md-8">
     <!-- Nav tabs -->
     <ul ng-init="getAll()" class="nav nav-tabs" role="tablist">
      <li ng-repeat="budget in budgets" class="{{$first ? 'active' : ''}}" role="presentation"><a href="#{{budget['Budget ID']}}" aria-controls="{{budget['Budget ID']}}" role="tab" data-toggle="tab">{{budget["Budget Name"]}}</a></li>
      <li role="presentation"><a href="#budgetCreate" aria-controls="budgetCreate" role="tab" data-toggle="tab">New Budget</a></li>
 </ul>
     <!-- Tab panes -->
     <div class="tab-content">
      <div  ng-repeat="budget in budgets" role="tabpanel" class="tab-pane {{$first ? 'active' : ''}}" id="{{budget['Budget ID']}}">
       <div class="container col-md-12">
        <budget-table></budget-table>
   </div>
  </div> <!-- End budget table content -->
      <div role="tabpanel" class="tab-pane" id="budgetCreate">
       <budget-form></budget-form>
  </div> <!-- End new budget tab content -->

 </div> <!-- End tab contents -->
   </div><!-- End Container for tabs of budgets -->
  </div><!-- end .container -->

  <footer class="text-center">
   <hr>
   <small>Coded by Alex Payne using AngularJS, PHP, and MySQL</small>
  </footer>
  <log-table></log-table><!-- Here for debugging -->
  <!-- AngularJS -->
  <script src="../js/vendor/angular.min.js"></script>
  <script src="../app/main.js"></script>
  <script src="../js/services/creationService.js"></script>
  <script src="../js/controllers/budgetController.js"></script>
  <script src="../js/filters/currency.js"></script>
  <script src="../js/controllers/budgetSelect.js"></script>
  <script src="../js/services/budgetFactory.js"></script>

  <!-- jQuery -->
  <script src="../js/vendor/jquery-2.1.4.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.4/ui-bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.4/ui-bootstrap-tpls.min.js"></script>
 </body>
</html>
