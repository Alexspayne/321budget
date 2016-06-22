<?php
session_start();
if( !$_SESSION['loggedInUser']) { // if user is not logged in
 header("Location: login.php");
}
include ('includes/functions.php');
////This code chooses the credentials and make a connection
include (getAppropriateConnectionBasedOnServer());
//Here begins the Post/Redirect/Get Design Pattern
if($_POST) {//if we have anything in post at all
 // Execute code (such as database updates) here.
 $bNameFromPost = $_POST['bName'];
 $bDescFromPost = $_POST['bDescription'];
 $createFromPost = $_POST["create"];
 if( isset( $createFromPost ) ) {
  $bName = $bDesc = "";
  if( !createFromPost ) {
   $_SESSION['alert'] = 'failure';
  } else {
   $bDesc = validateFormData( $bDescFromPost);
   $bName = validateFormData( $bNameFromPost);
  }
  $user = $_SESSION['loggedInUserId'];
  if(($bName || $bDesc) && $user){
   $result = addBudgetToDatabase($bName,$bDesc,$user,$conn);
   if( $result ){
    $_SESSION['alert'] = 'success';
    $detail = "Added budget: ".$bName." for: ". $_SESSION['loggedInUserName'];
    $type = "DEBUG";
    eventLog($conn,$detail,$type);
   }else{
    $detail = "Failed to add budget: " . mysqli_error($conn);
    $type = "DEBUG";
    eventLog($conn,$detail,$type);
    echo '<div class="alert alert-danger">
    <a href="#"  class="close" data-dismiss="alert" aria-label="close">
        &times;
    </a>
    The Query has failed!
    <br>
    Query: ' . $query .'<br>Error: ' .mysqli_error($conn).'</div>';
   }
  }
 }

 // Redirect to this page. This clears the POST and GET variables.
 header("Location: " . $_SERVER['REQUEST_URI']);
 exit();
}/* End PRG  */

//handle alerts

if( isset( $_SESSION['alert'] ) ) {
 // new entry added
 if( $_SESSION['alert'] == 'success' ) {
  $alertMessage = '<div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Success! Record added to the budget ledger!</div>';
 }
 if( $_SESSION['alert'] == 'failure' ) {
  $alertMessage = '<div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please add an entry in the form of a dollar amount like 10 or 15.25.</div>';
  // entry deleted
 } elseif( $_SESSION['alert'] == 'deleted' ) {
  $alertMessage = '<div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Record '.$_SESSION['id'].' deleted from ledger!</div>' ;
 }
 $_SESSION['alert'] = null; //clear the alert so that it doesn’t show on reload
}
mysqli_close($conn);
include('includes/header.php');
echo $alertMessage;?>

<div class="container col-md-12">
 <h1>Create a New Budget</h1>
 <p>Fill in the required fields and click <strong>Create Budget</strong> to create a new budget</p>
 <div class="form-group">
  <form role="form" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>" method="post">
   <br>
   <div class="row">
    <div class="col-md-6">
     <small class="text-danger"> <?php echo $debitError; ?></small>
     <label for="bName">Budget Name</label>
     <input class="form-control" type="text" placeholder="Budget Name" name="bName">
    </div>
    <div class="col-md-6">
     <label for="bDescription">Description of Budget</label>
     <input class="form-control" type="text" placeholder="Description of Budget" name="bDescription">
    </div> <!-- End Detail Text Box-->
   </div>
   <div class="form-group row">
    <input id="budgetbutton" class = "btn btn-primary" type="submit" name="create" value="Create Budget">
   </div> <!-- End Entry Button -->
  </form>
 </div>
</div><!-- End Creation form -->

<?php
include (getAppropriateConnectionBasedOnServer());
generateLogTable($conn);
?>
<?php
include('includes/footer.php');
?>
