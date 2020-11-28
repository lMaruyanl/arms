<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values

$occupantsedit1 = 0;
$occupants=0;
$occupants_errcheck = 0;
$occupantedit =0;
$occupant_err="";
$roomnumber = 0;
$location = 0;
$fullname = "";
$birthday = date('Y-m-d');
$homeaddress = "";
$age = 0;
$phonenumber = "";
$guardianFullname = "";
$guardianphonenumber = 0;
$addressofpresent = "";
$reasonforleaving = "";
$yearsofstayinpresent = 0;
$nameofschoolwork = "";
$addressofschoolwork = "";
$positionincompany = "";
$nameofimmediatesupervisor = "";
$yearsinpresentposition = 0;
$paymentstatus = 3;
$haveaccount = 0;


$editroomnumber = 0;
$editlocation = 0;
$editfullname = "";
$editbirthday = date('Y-m-d');
$edithomeaddress = "";
$editage = 0;
$editphonenumber = "";
$editguardianFullname = "";
$editguardianphonenumber = 0;
$editaddressofpresent = "";
$editreasonforleaving = "";
$edityearsofstayinpresent = 0;
$editnameofschoolwork = "";
$editaddressofschoolwork = "";
$editpositionincompany = "";
$editnameofimmediatesupervisor = "";
$edityearsinpresentposition = 0;
$editTenant_id = 0;
$oldRoom = "";



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
$roomnumber_err ="";
$roomnumbertem = 0;
//$TenantIDEDIT
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST['editTen']))
  {

    if($_POST['editOldroomnumber'] != $_POST['editroomnumber'] && $_POST['editOldlocation'] != $_POST['editlocation']){

    
    $sql = "SELECT roomnumber FROM room WHERE roomnumber = ? AND location =?";

    if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("is", $param_roomnumber, $param_location);

        // Set parameters
        $param_roomnumber = trim($_POST["editroomnumber"]);
        $param_location = trim($_POST['editlocation']);

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // store result
            $stmt->store_result();

            if($stmt->num_rows == 0){
                $roomnumber_err = "Room does not exist.";
              echo "<script>alert('$roomnumber_err');
              location='tenantmanagement.php';
              </script> ";

            } else{
                $roomnumbertem = trim($_POST["editroomnumber"]);
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    $stmt->close();


    $editRoomShow = "SELECT number from room where roomnumber=? AND location=?";
    if($stmt = $mysqli->prepare($editRoomShow)){
      $stmt->bind_param("is", $romnumber, $locations);
        $romnumber = $_POST['editroomnumber'];
        $locations = $_POST['editlocation'];
        $stmt->execute();

        $result = $stmt->get_result();
        $roomEditResult = $result->fetch_assoc();
        $occupants_errcheck = $roomEditResult['number'];
        $stmt->close();
      }
      if($occupants_errcheck != 0){
        $occupant_err = "Room is Already Occupaid.";
      echo "<script>alert('$occupant_err');
      location='tenantmanagement.php';
      </script> ";
      }
    }
    
    if(empty($roomnumber_err) && $occupants_errcheck == 0){  
    $sqleditmp = "UPDATE tenant SET roomnumber =?, location=?, Tenant_Name=?, Birthdate=?, Home_Address=?, Tenant_Age=?, Tenant_Contact=?, Guardian_Name=?, Guardian_Contact=?, Address_Of_Present_Apartment=?, Reason_For_Leaving=?, Years_Of_Stay_In_Present_Apartment=?, Name_Of_Schoolwork=?, Address_Of_Schoolwork=?, Position_In_Company=?, Name_Of_Immediate_Supervisor=?, Number_Of_years_In_present_Position=?, occupants=? where Tenant_Id =?";
    if($stmt = $mysqli->prepare($sqleditmp)){
      $stmt->bind_param("issssisssssissssiii", $editroomnumber, $editlocation, $editfullname, $editbirthday, $edithomeaddress, $editage, $editphonenumber, $editguardianFullname, $editguardianphonenumber, $editaddressofpresent, $editreasonforleaving, $edityearsofstayinpresent, $editnameofschoolwork, $editaddressofschoolwork, $editpositionincompany, $editnameofimmediatesupervisor, $edityearsinpresentposition, $occupantsedit1, $editTenant_id);
      $editroomnumber = $_POST['editroomnumber'];
      $editlocation = $_POST['editlocation'];
      $editfullname = $_POST['editfullname'];
      $editbirthday = $_POST['editbirthday'];
      $edithomeaddress = $_POST['edithomeaddress'];
      $editfrom = new DateTime($_POST['editbirthday']);
      $editto   = new DateTime('today');
      $editage = $editfrom->diff($editto)->y;
      $editphonenumber = $_POST['editphonenumber'];
      $editguardianFullname = $_POST['editguardianFullname'];
      $editguardianphonenumber = $_POST['editguardianphonenumber'];
      $editaddressofpresent = $_POST['editaddressofpresent'];
      $editreasonforleaving = $_POST['editreasonforleaving'];
      $edityearsofstayinpresent = $_POST['edityearsofstayinpresent'];
      $editnameofschoolwork = $_POST['editnameofschoolwork'];
      $editaddressofschoolwork = $_POST['editaddressofschoolwork'];
      $editpositionincompany = $_POST['editpositionincompany'];
      $editnameofimmediatesupervisor = $_POST['editNameimmediatesupervisor'];
      $edityearsinpresentposition = $_POST['edityearsinpresentposition'];
      $occupantsedit1 = $_POST['editoccupants'];
      $editTenant_id = $_POST['edittetenantID'];
        $stmt->execute();
      }
        $stmt->close();


        //$editsqld2 = "UPDATE room SET number=?  where roomnumber=? AND location=?";
        //if($stmt = $mysqli->prepare($editsqld2)){
        //  $stmt->bind_param("iis",$editparam_newOcc1, $roomnumberedit2, $locationedit2);
        // $editparam_newOcc1 = $_POST['editoccupants'];
        //  $roomnumberedit2 = $_POST['editroomnumber'];
        //  $locationedit2 = $_POST['editlocation'];
        //    $stmt->execute();
        //    $stmt->close();
        //  }
        //  header("location: tenantmanagement.php");
      
        $occupantupdate = "UPDATE room SET number=?  where roomnumber =? AND location=?";  
        if($stmt = $mysqli->prepare($occupantupdate)){
          $stmt->bind_param("iis",$occupants, $roomnumber, $location);
          $occupants = $_POST['editoccupants'];
          $roomnumber = $_POST['editroomnumber'];
          $location = $_POST['editlocation'];
            $stmt->execute();
            $stmt->close();
            echo "<script>alert('$location');
                      location='tenantmanagement.php';
                      </script> ";
    
          }
        
      header("location: tenantmanagement.php");
      }
  }
  if(isset($_POST['addTen']))
  {
        // Prepare an insert statement
        // Prepare a select statement
        $sql = "SELECT roomnumber FROM room WHERE roomnumber = ? AND location =?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("is", $param_roomnumber, $param_location);

            // Set parameters
            $param_roomnumber = trim($_POST["roomnumber"]);
            $param_location = trim($_POST['location']);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();

                if($stmt->num_rows == 0){
                    $roomnumber_err = "Room does not exist.";
                  echo "<script>alert('$roomnumber_err');
                  location='tenantmanagement.php';
                  </script> ";

                } else{
                    $roomnumbertem = trim($_POST["roomnumber"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();


        $editRoomShow = "SELECT number from room where roomnumber=? AND location=?";
        if($stmt = $mysqli->prepare($editRoomShow)){
          $stmt->bind_param("is", $romnumber, $locations);
            $romnumber = $_POST['roomnumber'];
            $locations = $_POST['location'];
            $stmt->execute();
  
            $result = $stmt->get_result();
            $roomEditResult = $result->fetch_assoc();
            $occupants_errcheck = $roomEditResult['number'];
            $stmt->close();
          }
          if($occupants_errcheck != 0){
            $occupant_err = "Room is Already Occupaid.";
          echo "<script>alert('$occupant_err');
          location='tenantmanagement.php';
          </script> ";
          }



        if(empty($roomnumber_err) && $occupants_errcheck == 0){  
        $sql = "INSERT INTO tenant (roomnumber, location, Tenant_Name, Birthdate, Home_Address, Tenant_Age, Tenant_Contact, Guardian_Name, Guardian_Contact, Address_Of_Present_Apartment, Reason_For_Leaving, Years_Of_Stay_In_Present_Apartment, Name_Of_Schoolwork, Address_Of_Schoolwork, Position_In_Company, Name_Of_Immediate_Supervisor, Number_Of_years_In_present_Position, Payment_Status, haveAcc, occupants, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("issssisssssissssiiiis", $roomnumber, $location, $fullname, $birthday, $homeaddress, $age, $phonenumber, $guardianFullname, $guardianphonenumber, $addressofpresent, $reasonforleaving, $yearsofstayinpresent, $nameofschoolwork, $addressofschoolwork, $positionincompany, $nameofimmediatesupervisor, $yearsinpresentposition, $paymentstatus, $haveaccount, $occupants, $status);
            $roomnumber = $_POST['roomnumber'];
            $location = $_POST['location'];
            $fullname = $_POST['fullname'];
            $birthday = $_POST['birthday'];
            $homeaddress = $_POST['homeaddress'];
            $from = new DateTime($_POST['birthday']);
            $to   = new DateTime('today');
            $age = $from->diff($to)->y;
            $phonenumber = $_POST['phonenumber'];
            $guardianFullname = $_POST['guardianFullname'];
            $guardianphonenumber = $_POST['guardianphonenumber'];
            $addressofpresent = $_POST['addressofpresent'];
            $reasonforleaving = $_POST['reasonforleaving'];
            $yearsofstayinpresent = $_POST['yearsofstayinpresent'];
            $nameofschoolwork = $_POST['nameofschoolwork'];
            $addressofschoolwork = $_POST['addressofschoolwork'];
            $positionincompany = $_POST['positionincompany'];
            $nameofimmediatesupervisor = $_POST['Nameimmediatesupervisor'];
            $yearsinpresentposition = $_POST['yearsinpresentposition'];
            $paymentstatus = 3;
            $haveaccount = 0;
            $occupants = $_POST['occupants'];
            $status = "Pending";
            $stmt->execute();
        }
        // Close statement
        $stmt->close();
    // Close connection
    $occupantupdate = "UPDATE room SET number=?  where roomnumber =? AND location=?";  
    if($stmt = $mysqli->prepare($occupantupdate)){
      $stmt->bind_param("iis",$occupants, $roomnumber, $location);
      $occupants = $_POST['occupants'];
      $roomnumber = $_POST['roomnumber'];
      $location = $_POST['location'];
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('$location');
                  location='tenantmanagement.php';
                  </script> ";

      }
      header("location: tenantmanagement.php");
  }
}
      if(isset($_POST['paymentTransaction']))
      {
        $PaymentSQL = "INSERT INTO payment(Tenant_Id, Date_Started, date_of_transaction, Rent, Electric, Others, Late_Fee, Excess, Total_Due, Cash, Dep_Slip, validtil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = $mysqli->prepare($PaymentSQL)){

            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("issiiiiiiiss", $paymentTenantID, $DateStarted, $dateofTransaction, $rentPayment, $electricPayment, $Others, $LateFess, $ExcessPayment, $TotalDue, $CashPayment, $DepositSlip, $Validtil);
            $paymentTenantID = $_POST['PaymentTenantID'];
            $date11 = new DateTime($_POST['dateStartedpayment']);
            $result12 = $date11->format('Y-m-d');



            $DateStarted = $result12;
            $dateofTransaction = date('Y-m-d');
            $rentPayment =  $_POST['rentPayment'];
            $electricPayment =  $_POST['electricpayment'];
            $Others =  $_POST['OthersPayment'];
            $LateFess =  $_POST['latefees'];
            $ExcessPayment =  $_POST['excess'];
            $TotalDue =  $_POST['totalDueDate'];
            $CashPayment =  $_POST['cashpayment'];
            $DepositSlip =  $_POST['DepositSlip'];
            $date12 = new DateTime($_POST['validitymonth']);
            $result11 = $date12->format('Y-m-d');

            $Validtil = $result11;

            if($stmt->execute()){
                  // Redirect to login page
                  header("location: paymentmanagement.php");
              } else{
              echo "something went wrong";
              }
                $stmt->close();
        }
          // Close statement
          $paymentStatus = 3;
          $renvalidity = date('Y-m-d');
          $paymentTentID = $_POST['PaymentTenantID'];
          $PaymentUpdate = "UPDATE tenant SET 	Payment_Status =?, rentvalidity=?  where Tenant_Id =?";
          if($stmt = $mysqli->prepare($PaymentUpdate)){
            $stmt->bind_param("isi",$paymentStatus, $renvalidity, $paymentTentID);
            $paymentStatus = 1;
            $date100 = new DateTime($_POST['validitymonth']);
            $result100 = $date100->format('Y-m-d');


            $renvalidity = $result100;
            $paymentTentID = $_POST['PaymentTenantID'];
            if($stmt->execute()){
                  // Redirect to login page
                  header("location: paymentmanagement.php");
              } else{
              echo "something went wrong";
              }
              $stmt->close();
            }





         

      }
      if(isset($_POST['edittenantstatus']))
  {
    $occupantupdate2 = "UPDATE room SET number=? where roomnumber=? AND location =?";  
    if($stmt = $mysqli->prepare($occupantupdate2)){
      $stmt->bind_param("iis",$occupants12, $room12, $location12);
      $occupants12 = 0;
      $room12 = $_POST['editstatusroom'];
      $location12 = $_POST['editstatuslocation'];

        $stmt->execute();
        $stmt->close();
    }
    $occupantupdate = "UPDATE tenant SET status=?, roomnumber=?, location=?, occupants=? where Tenant_Id=?";  
    if($stmt = $mysqli->prepare($occupantupdate)){
      $stmt->bind_param("sisii",$occupants, $room, $location, $occu, $ID);
      $occupants = $_POST['editstatus1'];
      $room =0;
      $location = "Moved";
      $occu = 0;
      $ID = $_POST['editTenantid'];

        $stmt->execute();
        $stmt->close();
    }
    header("location: tenantmanagement.php");
  }
  $mysqli->close();
  // Close connection
}



?>

