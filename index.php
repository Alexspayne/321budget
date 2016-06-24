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
  <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" ng-controller="session as sesh">
   <div class="container-fluid">
    <div class="navbar-header">
     <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
     </button>
     <a class="navbar-brand" href="#/{{sesh.isLoggedIn ? '' : 'login'}}">321<strong>BUDGET</strong></a>
    </div>

    <div class="collapse navbar-collapse" id="navbar-collapse">
     <ul class="nav navbar-nav">
      <li ng-show="sesh.isLoggedIn">
       <a href="#/">Home</a>
      </li>

      <li>
       <a href="#/about">About</a>
      </li>
     </ul>

     <ul class="nav navbar-nav navbar-right" ng-show="sesh.isLoggedIn">
      <p class="navbar-text">Logged in as {{sesh.isLoggedIn ? sesh.isLoggedIn : "yourself... hopefully"}}</p>

      <li><a href="#/logout">Log out</a></li>
     </ul>

     <ul class="nav navbar-nav navbar-right" ng-show="!sesh.isLoggedIn">
      <li><a href="#/login">Log in</a></li>
     </ul>
    </div>

   </div>

  </nav>
  <div class="container">
   <div ng-view></div>
  </div><!-- end .container -->

  <footer class="text-center">
               <hr>
               <small>Coded by Alex Payne using AngularJS, PHP, and MySQL</small>
          </footer>
  <log-table></log-table><!-- Here for debugging -->
  <!-- AngularJS -->
  <script src="../js/vendor/angular.min.js"></script>
  <script src="../app/main.js"></script>
  <script src="../js/controllers/sessionController.js"></script>
  <script src="../js/services/creationService.js"></script>
  <script src="../js/controllers/budgetController.js"></script>
  <script src="../js/filters/currency.js"></script>
  <script src="../js/controllers/budgetSelect.js"></script>
  <script src="../js/services/budgetFactory.js"></script>

  <!-- Routes -->
  <script src="../js/vendor/angular-route.min.js"></script>
  <script src="../js/routeConfig.js"></script>

  <!-- jQuery -->
  <script src="../js/vendor/jquery-2.1.4.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.4/ui-bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.4/ui-bootstrap-tpls.min.js"></script>
 </body>
</html>
