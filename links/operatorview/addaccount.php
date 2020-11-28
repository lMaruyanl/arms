<?php
require_once "../config.php";
$username = $password ="";
$accounttype = "user";
$tenant_idddd = "";
      if($_SERVER["REQUEST_METHOD"] == "POST")
      {
          if(isset($_POST['addaccountid']))
          {
///////////////////////////////////////////////////////////////////////////////////////yS?O63lm&Ga~o4/B
$sqlAccount = "SELECT id FROM useraccount WHERE username = ?";
   if($stmt = $mysqli->prepare($sqlAccount))
   {
       $stmt->bind_param("s", $param_username1);
       $param_username1 = trim($_POST["TenantUsername"]);
       if($stmt->execute())
       {
           $stmt->store_result();
           if($stmt->num_rows == 1)
           {
             echo "<script>alert('The username was already taken');</script>";
           } else
           {
               $username = trim($_POST["TenantUsername"]);
               $password = trim($_POST["TenantPassword"]);
               $tenant_idddd = trim($_POST["Tennnnnn"]);

           }
       }else
       {
           echo "Oops! Something went wrong. Please try again later.";
       }
       $stmt->close();
   }

   $sql111 = "INSERT INTO useraccount (username, password, accounttype, Tenant_Id) VALUES (?, ?, ?, ?)";
   if($stmt = $mysqli->prepare($sql111))
   {
       $stmt->bind_param("sssi", $param_username, $param_password, $param_accounttype, $param_Tenant);
       $param_username = $username;
       $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
       $param_accounttype = $accounttype;
       $param_Tenant = $tenant_idddd;
       $stmt->execute();
   }
   $stmt->close();



   $sqld2Ac = "UPDATE tenant SET haveAcc =? where Tenant_Id =?";
     if($stmt = $mysqli->prepare($sqld2Ac)){
       $stmt->bind_param("ii", $paramHaveac, $param_Teennid);
       $paramHaveac = 1;
       $param_Teennid = $tenant_idddd;
         $stmt->execute();
         if($stmt->execute()){
               // Redirect to login page
               header("location: tenantmanagement.php");
           } else{
           echo "something went wrong";
           }
         $stmt->close();
       }
/////////////////////////////////////////////////////////////////////////////////////////
          }
          $mysqli->close();
      }

 ?>
