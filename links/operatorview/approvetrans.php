<?php

require_once "../config.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST['mainDe'])){
  $haha= $_POST['btnClickedValue'];
    $sqld2 = "UPDATE transient SET Transient_Status =? where Guest_Id =?";
    if($stmt = $mysqli->prepare($sqld2)){
      $stmt->bind_param("ii",$paramSta, $param_g);
      $paramSta = 1;
      $param_g = $haha;

        $stmt->execute();
        if($stmt->execute()){
              // Redirect to login page
              header("location: transient.php");
          } else{
          echo "something went wrong";
          }
        $stmt->close();
      }
    $mysqli->close();
  }
  if(isset($_POST['mainDe1'])){
  $haha = $_POST['btnClickedValued'];
    $sqld2 = "UPDATE transient SET Transient_Status =? where Guest_Id =?";
    if($stmt = $mysqli->prepare($sqld2)){
      $stmt->bind_param("ii",$paramSta, $param_g);
      $paramSta = 0;
      $param_g = $haha;

        $stmt->execute();
        if($stmt->execute()){
              // Redirect to login page
              header("location: transient.php");
          } else{
          echo "something went wrong";
          }
        $stmt->close();
      }
    $mysqli->close();
  }
}
 ?>
