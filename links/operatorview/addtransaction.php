<?php
// Include config file
require_once "../config.php";

$paymentTenantID = 0;
$DateStarted = date('Y-m-d');
$dateofTransaction = date('Y-m-d');
$rentPayment = 0;
$electricPayment = 0;
$Others = 0;
$LateFess = 0;
$ExcessPayment = 0;
$TotalDue = 0;
$CashPayment = 0;
$DepositSlip = "";
$Validtil = date('Y-m-d');

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST['paymentTransaction']))
  {
    $PaymentSQL = "INSERT INTO payment(Tenant_Id, Date_Started, date_of_transaction, Rent, Electric, Others, Late_Fee, Excess, Total_Due, Cash, Dep_Slip, validtil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if($stmt = $mysqli->prepare($PaymentSQL)){

        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("issiiiiiiiss", $paymentTenantID, $DateStarted, $dateofTransaction, $rentPayment, $electricPayment, $Others, $LateFess, $ExcessPayment, $TotalDue, $CashPayment, $DepositSlip, $Validtil);
        $paymentTenantID = $_POST['PaymentTenantID'];
        $DateStarted = new DateTime($_POST['dateStartedpayment']);
        $dateofTransaction = date('Y-m-d');
        $rentPayment =  $_POST['rentPayment'];
        $electricPayment =  $_POST['$electricPayment'];
        $Others =  $_POST['OthersPayment'];
        $LateFess =  $_POST['latefees'];
        $ExcessPayment =  $_POST['excess'];
        $TotalDue =  $_POST['totalDue'];
        $CashPayment =  $_POST['cashpayment'];
        $DepositSlip =  $_POST['DepositSlip'];
        $Validtil = new DateTime($_POST['validitymonth']);

        if($stmt->execute()){
              // Redirect to login page
              header("location: tenantmanagement.php");
          } else{
          echo "something went wrong";
          }
    }
    // Close statement
    $stmt->close();
      $mysqli->close();
// Close connection

  }
}

?>
