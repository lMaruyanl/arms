<?php
require_once "../config.php";
$dateAnnounced = date('Y-m-d');
$message1 = "";


if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST['addR']))
  {
    $message1 = $_POST['MessageA'];

      $sql = "INSERT INTO announcement (Announcement, Date_Of_Announcement) VALUES (?, ?)";

      if($stmt = $mysqli->prepare($sql)){
          // Bind variables to the prepared statement as parameters
          $stmt->bind_param("ss", $param_message, $param_date);

          // Set parameters
          $param_message = $message1;
          $param_date = $dateAnnounced;
          // Creates a password hash

          // Attempt to execute the prepared statement
          if($stmt->execute()){
              // Redirect to login page
              header("location: announcementmanagement.php");
          } else{
              echo "Something went wrong. Please try again later.";
          }
          $stmt->close();
          $mysqli->close();
      }
}





        if(isset($_POST['mainDe'])){
        $ForA = $_POST['btnClickedValue'];
        $paramRoomnumberTenant = $_POST['TenantRoomnumber'];
        $sqlsss = "DELETE FROM announcement WHERE A_ID =?";
        if($stmt = $mysqli->prepare($sqlsss)){
              // Bind variables to the prepared statement as parameters
              $stmt->bind_param("i", $param_A_ID);
              // Set parameters
              $param_A_ID = $ForA;
              $stmt->execute();
              if($stmt->execute()){
                  // Redirect to login page
                  header("location: announcementmanagement.php");
              } else{
                  echo "Something went wrong. Please try again later.";
              }
              // Attempt to execute the prepared statement

          }
          $stmt->close();


          $mysqli->close();
        }
}
// Close statement

 ?>
