
<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../index.php");
    exit;
}else {
        if($_SESSION["Usertype"] == "user")
        {
          session_start();
          $_SESSION["dddddd"] = $_SESSION["ttentantid"];
          header("location: user.php");

          exit;
        }else if($_SESSION["Usertype"] == "admin")
        {
          header("location: Admin.php");
          exit;
        }else if($_SESSION["Usertype"] == "operator")
        {
          header("location: operator.php");
          exit;
        }

}
?>
