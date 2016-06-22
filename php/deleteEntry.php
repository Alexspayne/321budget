<?php

function deleteEntry($entryID,$conn){
 
 $deleteFromPost = $entryID;


 if( isset($deleteFromPost) ) {
  $idToDelete = $deleteFromPost;
  $queryDelete = "DELETE FROM budgetentries WHERE entryid='$idToDelete'";
  $result = mysqli_query( $conn, $queryDelete );
  if( $result && $idToDelete) {
   $_SESSION['alert'] = 'deleted';
   $_SESSION['id'] = $idToDelete;
   //            header("Location: index.php?alert=deleted&id=".$idToDelete);
   $detail = "Deleted entry for: ". $_SESSION['loggedInUserName'];
   $type = "DEBUG";
   eventLog($conn,$detail,$type);
  } else {
   echo "Error updating record: " . mysqli_error($conn) . $idToDelete;
  }
 }
 
}
