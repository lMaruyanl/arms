<?PHP
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../index.php");
    exit;
}else{
  if(isset($_SESSION["Usertype"]) && $_SESSION["Usertype"] != "operator")
  {
    header("location: welcome.php");
    exit;
  }

  require_once "config.php";

  $new_password = $newconfirm_password = "";
  $new_password_err = $newconfirm_password_err = "";
  if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST['changep'])){

      if(empty(trim($_POST["new_password"]))){
          $new_password_err = "Please enter the new password.";
      } elseif(strlen(trim($_POST["new_password"])) < 6){
          $new_password_err = "Password must have atleast 6 characters.";
      } else{
          $new_password = trim($_POST["new_password"]);
      }

      // Validate confirm password
      if(empty(trim($_POST["confirm_password"]))){
          $confirm_password_err = "Please confirm the password.";
      } else{
          $confirm_password = trim($_POST["confirm_password"]);
          if(empty($new_password_err) && ($new_password != $confirm_password)){
              $confirm_password_err = "Password did not match.";
              echo "<script>alert('$confirm_password_err');
              location='operator.php';
              </script> ";
          }
      }

      // Check input errors before updating the database
      if(empty($new_password_err) && empty($confirm_password_err)){
          // Prepare an update statement
          $sql = "UPDATE useraccount SET password = ? WHERE id = ?";

          if($stmt = $mysqli->prepare($sql)){
              // Bind variables to the prepared statement as parameters
              $stmt->bind_param("si", $param_password, $param_id);

              // Set parameters
              $param_password = password_hash($new_password, PASSWORD_DEFAULT);
              $param_id = $_SESSION["id"];

              // Attempt to execute the prepared statement
              if($stmt->execute()){
                  // Password updated successfully. Destroy the session, and redirect to login page
                  echo "<script>alert('password changed successfully');
                  </script>";

              } else{
                  echo "Oops! Something went wrong. Please try again later.";
              }
          }

          // Close statement
          $stmt->close();
      }

      // Close connection
      $mysqli->close();


  }


  function fetch_data()
{
$conn = mysqli_connect("localhost", "root", "", "ramonadorm");
$output = '';
$sqlgg = "SELECT Tenant_Name, Date_Started, date_of_transaction, FORMAT(Rent,2) , FORMAT(Electric,2), FORMAT(Others,2), FORMAT(Late_Fee,2), FORMAT(Excess,2), FORMAT(Total_Due,2), FORMAT(Cash,2), Dep_Slip  FROM payment INNER JOIN tenant ON tenant.Tenant_Id=payment.Tenant_Id Where 1 AND MONTH(date_of_transaction) = ".trim($_POST['monthp'])." AND year(date_of_transaction) = ".trim($_POST['yearp']).";";
$result = mysqli_query($conn, $sqlgg);

while($row = mysqli_fetch_array($result))
{
$rent1 = $row["FORMAT(Rent,2)"];
$electric1 = $row["FORMAT(Electric,2)"];
$others1 = $row["FORMAT(Others,2)"];
$latefees1 = $row["FORMAT(Late_Fee,2)"];
$excess1 = $row["FORMAT(Excess,2)"];
$TotalDue1 = $row["FORMAT(Total_Due,2)"];
$cash1 = $row["FORMAT(Cash,2)"];

$output .= '<tr>
<td>'.$row["Tenant_Name"].'</td>
<td>'.$row["Date_Started"].'</td>
<td>'.$row["date_of_transaction"].'</td>
<td>'.$rent1.'</td>
<td>'.$electric1.'</td>
<td>'.$others1.'</td>
<td>'.$latefees1.'</td>
<td>'.$excess1.'</td>
<td>'.$TotalDue1.'</td>
<td>'.$cash1.'</td>
<td>'.$row["Dep_Slip"].'</td>
</tr>

';
}
return $output;
}







function fetch_data1()
{
$conn1 = mysqli_connect("localhost", "root", "", "ramonadorm");
$output1 = '';
$sqlgg1 = "SELECT * FROM tenant";
$result1 = mysqli_query($conn1, $sqlgg1);

while($row = mysqli_fetch_array($result1))
{


$output1 .= '<tr>
<td>'.$row["Tenant_Name"].'</td>
<td>'.$row["Room_Number"].'</td>
<td>'.$row["Tenant_Contact"].'</td>
<td>'.$row["Guardian_Name"].'</td>
<td>'.$row["Guardian_Contact"].'</td>
<td>'.$row["Home_Address"].'</td>
</tr>

';
}
return $output1;
}



function fetch_data2()
{
$conn2 = mysqli_connect("localhost", "root", "", "ramonadorm");
$output2 = '';
$sqlgg2 = "SELECT * FROM transient INNER JOIN tenant ON tenant.Tenant_Id=transient.Tenant_Id;";
$result2 = mysqli_query($conn2, $sqlgg2);

while($row = mysqli_fetch_array($result2))
{


$output2 .= '<tr>
<td>'.$row["Guest_Name"].'</td>
<td>'.$row["Contact"].'</td>
<td>'.$row["Duration"].'</td>
<td>'.$row["Tenant_Name"].'</td>
<td>'.$row["Room_Number"].'</td>
</tr>

';
}
return $output2;
}



if(isset($_POST["PrintPayment"]))
{
require_once('tcpdf.php');

$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf->SetCreator(PDF_CREATOR);

$obj_pdf->SetTitle("Payment Reports");
$obj_pdf->SetHeaderData('', '', '', PDF_HEADER_STRING);
$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$obj_pdf->SetDefaultMonospacedFont('helvetica');
$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
$obj_pdf->setPrintHeader(false);
$obj_pdf->setPrintFooter(false);
$obj_pdf->SetAutoPageBreak(TRUE, 10);
$obj_pdf->SetFont('helvetica', '', 11);
$obj_pdf->AddPage();
$obj_pdf->setPageOrientation('L');
$content = '';
$content .= '
<h1 align="center">A&nbsp;P&nbsp;A&nbsp;R&nbsp;T&nbsp;M&nbsp;E&nbsp;N&nbsp;T&nbsp;&nbsp;&nbsp;R&nbsp;E&nbsp;N&nbsp;T&nbsp;A&nbsp;L </h1>
<h1 align="center">M&nbsp;A&nbsp;N&nbsp;A&nbsp;G&nbsp;E&nbsp;M&nbsp;E&nbsp;N&nbsp;T&nbsp;&nbsp;&nbsp;S&nbsp;Y&nbsp;S&nbsp;T&nbsp;E&nbsp;M </h1>
<p align="center"><i><small>Address: </small></i></p>
<p align="center"><i><small>Number: </small></i></p>
<h4 align="center" style="color:red;">Payment Report</h4><br />
<table border="1" cellspacing="0" cellpadding="3">
<tr>
<th width="8%"><strong>Name</strong></th>
<th width="9%"><strong>Date Started</strong></th>
<th width="9%"><strong>Date of transaction</strong></th>
<th width="10%"><strong>Rent</strong></th>
<th width="10%"><strong>Electric</strong></th>
<th width="10%"><strong>Others</strong></th>
<th width="10%"><strong>Late fees</strong></th>
<th width="10%"><strong>Excess</strong></th>
<th width="10%"><strong>Total Due</strong></th>
<th width="10%"><strong>Cash Paid</strong></th>
<th width="6%"><strong>Deposit Slip</strong></th>
</tr>
';
$content .= fetch_data();
$content .= '</table>';
$obj_pdf->writeHTML($content);
$obj_pdf->Output('paymentreport.pdf', 'I');
}


if(isset($_POST["PrintTenant"]))
{
require_once('tcpdf.php');

$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf->SetCreator(PDF_CREATOR);

$obj_pdf->SetTitle("Tenant List Reports");
$obj_pdf->SetHeaderData('', '', '', PDF_HEADER_STRING);
$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$obj_pdf->SetDefaultMonospacedFont('helvetica');
$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
$obj_pdf->setPrintHeader(false);
$obj_pdf->setPrintFooter(false);
$obj_pdf->SetAutoPageBreak(TRUE, 10);
$obj_pdf->SetFont('helvetica', '', 11);
$obj_pdf->AddPage();
$obj_pdf->setPageOrientation('L');
$content = '';
$content .= '
<h1 align="center">A&nbsp;P&nbsp;A&nbsp;R&nbsp;T&nbsp;M&nbsp;E&nbsp;N&nbsp;T&nbsp;&nbsp;&nbsp;R&nbsp;E&nbsp;N&nbsp;T&nbsp;A&nbsp;L </h1>
<h1 align="center">M&nbsp;A&nbsp;N&nbsp;A&nbsp;G&nbsp;E&nbsp;M&nbsp;E&nbsp;N&nbsp;T&nbsp;&nbsp;&nbsp;S&nbsp;Y&nbsp;S&nbsp;T&nbsp;E&nbsp;M </h1>
<p align="center"><i><small>Address: </small></i></p>
<p align="center"><i><small>Number: </small></i></p>
<h4 align="center" style="color:red;">Tenant List Report</h4><br />
<table border="1" cellspacing="0" cellpadding="3">
<tr>
<th width="15%"><strong>Name</strong></th>
<th width="10%"><strong>Room Number</strong></th>
<th width="15%"><strong>Contact Numbern</strong></th>
<th width="15%"><strong>Guardian Name</strong></th>
<th width="15%"><strong>Guardian Contact Number</strong></th>
<th width="30%"><strong>Home Address</strong></th>
</tr>
';
$content .= fetch_data1();
$content .= '</table>';
$obj_pdf->writeHTML($content);
$obj_pdf->Output('tenantlistreport.pdf', 'I');
}




if(isset($_POST["PrintTransient"]))
{
require_once('tcpdf.php');

$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf->SetCreator(PDF_CREATOR);

$obj_pdf->SetTitle("Transient List Reports");
$obj_pdf->SetHeaderData('', '', '', PDF_HEADER_STRING);
$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$obj_pdf->SetDefaultMonospacedFont('helvetica');
$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
$obj_pdf->setPrintHeader(false);
$obj_pdf->setPrintFooter(false);
$obj_pdf->SetAutoPageBreak(TRUE, 10);
$obj_pdf->SetFont('helvetica', '', 11);
$obj_pdf->AddPage();
$obj_pdf->setPageOrientation('L');
$content = '';
$content .= '
<h1 align="center">A&nbsp;P&nbsp;A&nbsp;R&nbsp;T&nbsp;M&nbsp;E&nbsp;N&nbsp;T&nbsp;&nbsp;&nbsp;R&nbsp;E&nbsp;N&nbsp;T&nbsp;A&nbsp;L </h1>
<h1 align="center">M&nbsp;A&nbsp;N&nbsp;A&nbsp;G&nbsp;E&nbsp;M&nbsp;E&nbsp;N&nbsp;T&nbsp;&nbsp;&nbsp;S&nbsp;Y&nbsp;S&nbsp;T&nbsp;E&nbsp;M </h1>
<p align="center"><i><small>Address: </small></i></p>
<p align="center"><i><small>Number: </small></i></p>
<h4 align="center" style="color:red;">Transient List Report</h4><br />
<table border="1" cellspacing="0" cellpadding="3">
<tr>
<th width="20%"><strong>Guest Name</strong></th>
<th width="20%"><strong>Contact Number</strong></th>
<th width="20%"><strong>Duration Of Stay id days</strong></th>
<th width="20%"><strong>Requested By</strong></th>
<th width="20%"><strong>Room Number of tenant</strong></th>
</tr>
';
$content .= fetch_data2();
$content .= '</table>';
$obj_pdf->writeHTML($content);
$obj_pdf->Output('transientlistreport.pdf', 'I');
}

}
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Apartment Rental Management System</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<link rel="icon" href="../icon/main.png">

</head>
<style>
body{
  background-image: url('../icon/bg5.webp');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover;
  background-blend-mode: hue;
  background-color: rgba(255, 255, 255, 0.85);
}
@keyframes swing {
  0% {
    transform: rotate(0deg);
  }
  10% {
    transform: rotate(10deg);
  }
  30% {
    transform: rotate(0deg);
  }
  40% {
    transform: rotate(-10deg);
  }
  50% {
    transform: rotate(0deg);
  }
  60% {
    transform: rotate(5deg);
  }
  70% {
    transform: rotate(0deg);
  }
  80% {
    transform: rotate(-5deg);
  }
  100% {
    transform: rotate(0deg);
  }
}

@keyframes sonar {
  0% {
    transform: scale(0.9);
    opacity: 1;
  }
  100% {
    transform: scale(2);
    opacity: 0;
  }
}
body {
  font-size: 0.9rem;
}
.page-wrapper .sidebar-wrapper,
.sidebar-wrapper .sidebar-brand > a,
.sidebar-wrapper .sidebar-dropdown > a:after,
.sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu li a:before,
.sidebar-wrapper ul li a i,
.page-wrapper .page-content,
.sidebar-wrapper .sidebar-search input.search-menu,
.sidebar-wrapper .sidebar-search .input-group-text,
.sidebar-wrapper .sidebar-menu ul li a,
#show-sidebar,
#close-sidebar {
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  -ms-transition: all 0.3s ease;
  -o-transition: all 0.3s ease;
  transition: all 0.3s ease;
}

/*----------------page-wrapper----------------*/

.page-wrapper {
  height: 100vh;
}

.page-wrapper .theme {
  width: 40px;
  height: 40px;
  display: inline-block;
  border-radius: 4px;
  margin: 2px;
}

.page-wrapper .theme.chiller-theme {
  background: #1e2229;
}

/*----------------toggeled sidebar----------------*/

.page-wrapper.toggled .sidebar-wrapper {
  left: 0px;
}

@media screen and (min-width: 768px) {
  .page-wrapper.toggled .page-content {
    padding-left: 300px;
  }
}
/*----------------show sidebar button----------------*/
#show-sidebar {
  position: fixed;
  z-index: 999;
  left: 0;
  top: 10px;
  border-radius: 0 4px 4px 0px;
  width: 35px;
  transition-delay: 0.3s;
}
.page-wrapper.toggled #show-sidebar {
  left: -40px;
}
/*----------------sidebar-wrapper----------------*/

.sidebar-wrapper {
  width: 260px;
  height: 100%;
  max-height: 100%;
  position: fixed;
  top: 0;
  left: -300px;
  z-index: 999;
}

.sidebar-wrapper ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.sidebar-wrapper a {
  text-decoration: none;
}

/*----------------sidebar-content----------------*/

.sidebar-content {
  max-height: calc(100% - 30px);
  height: calc(100% - 30px);
  overflow-y: auto;
  position: relative;
}

.sidebar-content.desktop {
  overflow-y: hidden;
}

/*--------------------sidebar-brand----------------------*/

.sidebar-wrapper .sidebar-brand {
  padding: 10px 20px;
  display: flex;
  align-items: center;
}

.sidebar-wrapper .sidebar-brand > a {
  text-transform: uppercase;
  font-weight: bold;
  flex-grow: 1;
}

.sidebar-wrapper .sidebar-brand #close-sidebar {
  cursor: pointer;
  font-size: 20px;
}
/*--------------------sidebar-header----------------------*/

.sidebar-wrapper .sidebar-header {
  padding: 20px;
  overflow: hidden;
}

.sidebar-wrapper .sidebar-header .user-pic {
  float: left;
  width: 60px;
  padding: 2px;
  border-radius: 12px;
  margin-right: 15px;
  overflow: hidden;
}

.sidebar-wrapper .sidebar-header .user-pic img {
  object-fit: cover;
  height: 100%;
  width: 100%;
}

.sidebar-wrapper .sidebar-header .user-info {
  float: left;
}

.sidebar-wrapper .sidebar-header .user-info > span {
  display: block;
}
.user-name{
  font-size: 13px;
}
.sidebar-wrapper .sidebar-header .user-info .user-role {
  font-size: 12px;
}

.sidebar-wrapper .sidebar-header .user-info .user-status {
  font-size: 11px;
  margin-top: 4px;
}

.sidebar-wrapper .sidebar-header .user-info .user-status i {
  font-size: 8px;
  margin-right: 4px;
  color: #5cb85c;
}

/*-----------------------sidebar-search------------------------*/

.sidebar-wrapper .sidebar-search > div {
  padding: 10px 20px;
}

/*----------------------sidebar-menu-------------------------*/

.sidebar-wrapper .sidebar-menu {
  padding-bottom: 10px;
}

.sidebar-wrapper .sidebar-menu .header-menu span {
  font-weight: bold;
  font-size: 16px;
  padding: 15px 20px 5px 20px;
  display: inline-block;

}

.sidebar-wrapper .sidebar-menu ul li a {
  display: inline-block;
  width: 100%;
  text-decoration: none;
  position: relative;
  padding: 8px 30px 8px 20px;
}

.sidebar-wrapper .sidebar-menu ul li a i {
  margin-right: 10px;
  font-size: 12px;
  width: 30px;
  height: 30px;
  line-height: 30px;
  text-align: center;
  border-radius: 4px;
}

.sidebar-wrapper .sidebar-menu ul li a:hover > i::before {
  display: inline-block;
  animation: swing ease-in-out 0.5s 1 alternate;
}

.sidebar-wrapper .sidebar-menu .sidebar-dropdown > a:after {
  font-family: "Font Awesome 5 Free";
  font-weight: 900;
  content: "\f105";
  font-style: normal;

  display: inline-block;
  font-style: normal;
  font-variant: normal;
  text-rendering: auto;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  background: 0 0;
  position: absolute;
  right: 15px;
  top: 14px;
}

.sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu ul {
  padding: 5px 0;
}

.sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu li {
  padding-left: 25px;
  font-size: 13px;
}

.sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu li a:before {
  content: "\f111";
  font-family: "Font Awesome 5 Free";
  font-weight: 400;
  font-style: normal;
  display: inline-block;
  text-align: center;
  text-decoration: none;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  margin-right: 10px;
  font-size: 8px;
}

.sidebar-wrapper .sidebar-menu ul li a span.label,
.sidebar-wrapper .sidebar-menu ul li a span.badge {
  float: right;
  margin-top: 8px;
  margin-left: 5px;
}

.sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu li a .badge,
.sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu li a .label {
  float: right;
  margin-top: 0px;

}

.sidebar-wrapper .sidebar-menu .sidebar-submenu {
  display: none;
}

.sidebar-wrapper .sidebar-menu .sidebar-dropdown.active > a:after {
  transform: rotate(90deg);
  right: 17px;
}

/*--------------------------side-footer------------------------------*/

.sidebar-footer {
  position: absolute;
  width: 100%;
  bottom: 0;
  display: flex;
}

.sidebar-footer > a {
  font-size: 12px;
  flex-grow: 1;
  text-align: center;
  height: 30px;
  line-height: 30px;
  position: relative;
}
#FooA, #FooB{
  font-family: calibri;
}
.sidebar-footer > a .notification {
  position: absolute;
  top: 0;
}

.badge-sonar {
  display: inline-block;
  background: #980303;
  border-radius: 50%;
  height: 8px;
  width: 8px;
  position: absolute;
  top: 0;
}

.badge-sonar:after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  border: 2px solid #980303;
  opacity: 0;
  border-radius: 50%;
  width: 100%;
  height: 100%;
  animation: sonar 1.5s infinite;
}

/*--------------------------page-content-----------------------------*/

.page-wrapper .page-content {
  display: inline-block;
  width: 100%;
  padding-left: 0px;
  padding-top: 20px;
}

.page-wrapper .page-content > div {
  padding: 20px 40px;
}

.page-wrapper .page-content {
  overflow-x: hidden;
}

/*------scroll bar---------------------*/

::-webkit-scrollbar {
  width: 5px;
  height: 7px;
}
::-webkit-scrollbar-button {
  width: 0px;
  height: 0px;
}
::-webkit-scrollbar-thumb {
  background: #525965;
  border: 0px none #ffffff;
  border-radius: 0px;
}
::-webkit-scrollbar-thumb:hover {
  background: #525965;
}
::-webkit-scrollbar-thumb:active {
  background: #525965;
}
::-webkit-scrollbar-track {
  background: transparent;
  border: 0px none #ffffff;
  border-radius: 50px;
}
::-webkit-scrollbar-track:hover {
  background: transparent;
}
::-webkit-scrollbar-track:active {
  background: transparent;
}
::-webkit-scrollbar-corner {
  background: transparent;
}


/*-----------------------------chiller-theme-------------------------------------------------*/

.chiller-theme .sidebar-wrapper {
    background-image: url(../icon/backg1.jpg);
     background-repeat: no-repeat, repeat;
     background-blend-mode: hue;
     background-position: center;
     background-size: cover;
     background-color: rgba(29, 29, 29, 0.65);

}

.chiller-theme .sidebar-wrapper .sidebar-header,
.chiller-theme .sidebar-wrapper .sidebar-search,
.chiller-theme .sidebar-wrapper .sidebar-menu {
    border-top: 1px solid #3a3f48;
}

.chiller-theme .sidebar-wrapper .sidebar-search input.search-menu,
.chiller-theme .sidebar-wrapper .sidebar-search .input-group-text {
    border-color: transparent;
    box-shadow: none;
}

.chiller-theme .sidebar-wrapper .sidebar-header .user-info .user-role,
.chiller-theme .sidebar-wrapper .sidebar-header .user-info .user-status,
.chiller-theme .sidebar-wrapper .sidebar-search input.search-menu,
.chiller-theme .sidebar-wrapper .sidebar-search .input-group-text,
.chiller-theme .sidebar-wrapper .sidebar-brand>a,
.chiller-theme .sidebar-wrapper .sidebar-menu ul li a,
.chiller-theme .sidebar-footer>a {
    color: #818896;
}

.chiller-theme .sidebar-wrapper .sidebar-menu ul li:hover>a,
.chiller-theme .sidebar-wrapper .sidebar-menu .sidebar-dropdown.active>a,
.chiller-theme .sidebar-wrapper .sidebar-header .user-info,
.chiller-theme .sidebar-wrapper .sidebar-brand>a:hover,
.chiller-theme .sidebar-footer>a:hover i {
    color: #b8bfce;
}

.page-wrapper.chiller-theme.toggled #close-sidebar {
    color: #bdbdbd;
}

.page-wrapper.chiller-theme.toggled #close-sidebar:hover {
    color: #ffffff;
}

.chiller-theme .sidebar-wrapper ul li:hover a i,
.chiller-theme .sidebar-wrapper .sidebar-dropdown .sidebar-submenu li a:hover:before,
.chiller-theme .sidebar-wrapper .sidebar-search input.search-menu:focus+span,
.chiller-theme .sidebar-wrapper .sidebar-menu .sidebar-dropdown.active a i {
    color: #16c7ff;
    text-shadow:0px 0px 10px rgba(22, 199, 255, 0.5);
}

.chiller-theme .sidebar-wrapper .sidebar-menu ul li a i,
.chiller-theme .sidebar-wrapper .sidebar-menu .sidebar-dropdown div,
.chiller-theme .sidebar-wrapper .sidebar-search input.search-menu,
.chiller-theme .sidebar-wrapper .sidebar-search .input-group-text {
    background: rgba(29, 29, 29, 0.5);
}

.chiller-theme .sidebar-wrapper .sidebar-menu .header-menu span {
    color: #b8bfce;
}

.chiller-theme .sidebar-footer {
    background: #3a3f48;
    box-shadow: 0px -1px 5px #282c33;
    border-top: 1px solid #464a52;
}

.chiller-theme .sidebar-footer>a:first-child {
    border-left: none;
}

.chiller-theme .sidebar-footer>a:last-child {
    border-right: none;
}



.responsive-video {
    position: relative;
    padding-bottom: 56.25%;
    padding-top: 60px; overflow: hidden;
}

.responsive-video iframe,
.responsive-video object,
.responsive-video embed {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>
<script>
jQuery(function ($) {

    $(".sidebar-dropdown > a").click(function() {
  $(".sidebar-submenu").slideUp(200);
  if (
    $(this)
      .parent()
      .hasClass("active")
  ) {
    $(".sidebar-dropdown").removeClass("active");
    $(this)
      .parent()
      .removeClass("active");
  } else {
    $(".sidebar-dropdown").removeClass("active");
    $(this)
      .next(".sidebar-submenu")
      .slideDown(200);
    $(this)
      .parent()
      .addClass("active");
  }
});

$("#close-sidebar").click(function() {
  $(".page-wrapper").removeClass("toggled");
});
$("#show-sidebar").click(function() {
  $(".page-wrapper").addClass("toggled");
});




});
</script>
<body>


<div class="page-wrapper chiller-theme toggled">
  <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
    <i class="fas fa-bars"></i>
  </a>
  <nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">
      <div class="sidebar-brand">
        <a href="#">Apartment Rental Management System</a>
        <div id="close-sidebar">
          <i class="fas fa-times"></i>
        </div>
      </div>
      <div class="sidebar-header">
        <div class="user-pic">
          <img class="img-responsive img-rounded" src="../icon/admin.png"
            alt="User picture">
        </div>
        <div class="user-info">
          <span class="user-name">
            <strong><?php echo $_SESSION["username"]; ?></strong>
          </span>
          <span class="user-role">Operator </span>
          <span class="user-status">
            <i class="fa fa-circle"></i>
            <span>Online</span>
          </span>
        </div>
      </div>
      <!-- sidebar-header  -->
      <div class="sidebar-search">
        <div>
          <div class="input-group" style="text-align:center;">
            <a href="" style="background-color:transparent;color:#b8bfce;border-right:none;border-left:none;border-color:#b8bfce;" class="btn btn-lg btn-default">ANNOUNCEMENT</a>
          </div>
        </div>
      </div>
      <!-- sidebar-search  -->
      <div class="sidebar-menu">
        <ul>
          <li class="header-menu">
            <span>General</span>
          </li>
          <li class="sidebar-dropdown">
            <a href="#">
              <i class="fa fa-info-circle"></i>
              <span style="font-size:13px;">Management</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="operatorview/tenantmanagement.php" target="myIframe">Tenant Management</a>
                </li>
                <li>
                  <a href="operatorview/roommanagement.php" target="myIframe">Room Management</a>
                </li>
                <li>
                  <a href="operatorview/transient.php" target="myIframe">Reservations</a>
                </li>
                <li>
                  <a href="operatorview/paymentmanagement.php" target="myIframe">Payment Management</a>
                </li>
                <li>
                  <a href="operatorview/archives.php" target="myIframe">Archives</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="sidebar-dropdown">
            <a href="#">
              <i class="fa fa-info-circle"></i>
              <span style="font-size:13px;">Pending/Not paid</span>
            </a>

            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="operatorview/pendingnot.php" target="myIframe">Tenant</a>
                </li>


              </ul>
            </div>
          </li>
          <li>

          <a href="#addRoom" data-toggle="modal">
            <i class="fa fa-file-pdf-o" style = "color: red;" ></i>
            <span style="Font-size:13px;">Print Payment Reports</span>
          </a>
          <a href="#addRoom1" data-toggle="modal">
            <i class="fa fa-file-pdf-o" style = "color: red;" ></i>
            <span style="Font-size:13px;">Print Tenant List</span>
          </a>
          <!--<a href="#addRoom2" data-toggle="modal">
            <i class="fa fa-file-pdf-o" style = "color: red;" ></i>
            <span style="Font-size:13px;">Print Transient List</span>
          </a>-->
        </li>







        </ul>
      </div>
      <!-- sidebar-menu  -->
    </div>
    <!-- sidebar-content  -->

    <div class="sidebar-footer">

      <a href="#Changepass" data-toggle="modal">
        <i class="fa fa-key"> <span id = "FooB">Change Password</span></i>

      </a>


      <a href="logout.php">
        <i class="fa fa-power-off" title="Log Out"> <span id = "FooA">logout</span> </i>
      </a>

    </div>
  </nav>
  <!-- sidebar-wrapper  -->


  <div id="Changepass" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="modal-header">
            <h4 class="modal-title">Change Password <span class="badge badge-info">Format : 6 characters, no spaces</span></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">

                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>" pattern="[^ ]+.{5,}">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($newconfirm_password_err)) ? 'has-error' : ''; ?>" >
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" pattern="[^ ]+.{5,}">
                <span class="help-block"><?php echo $newconfirm_password_err; ?></span>
            </div>
          </div>
          <div class="modal-footer">

                <a class="btn btn-link" href="Admin.php">Cancel</a>
                <input type="submit" onsubmit="setTimeout(function () { window.location.reload(); }, 10)" class="btn btn-success" value="Submit" name="changep">

          </div>
        </form>
      </div>
    </div>
  </div>
  <main class="page-content">

    <div class="embed-responsive" style="height:590px; width:98%; left:1%; text-align:center;" >
    <iframe src="operatorview/announcementmanagement.php"class="embed-responsive-item" name="myIframe" allowfullscreen></iframe>


    </div>
  </main>

  <div id="addRoom" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
          <div class="modal-header">
            <h4 class="modal-title">Please Select Month and Year to print</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
             <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
             <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
             <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
             <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
            <div class="form-group">
              <label>Month</label>
              <input class="form-control date-own"  name = "monthp" type="text">

            </div>
            <div class="form-group">
              <label>Year</label>
              <input class="form-control date-own1" name = "yearp" type="text">
              <p class="text-warning"><small>If the Month or year is not on the database, it will print nothing</small></p>
            </div>
            <script type="text/javascript">
                $('.date-own').datepicker({
                  format: "mm",
                  startView: "months",
                  minViewMode: "months"
                 });
                 $('.date-own1').datepicker({
                   format: "yyyy",
                   startView: "years",
                   minViewMode: "years"
                  });
            </script>

          </div>
          <div class="modal-footer">




          <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
            <input type="submit" onsubmit="setTimeout(function () { window.location.reload(); }, 10)" class="btn btn-danger" data-dismiss="static" name ="PrintPayment" value="Print">
          </div>
        </form>
      </div>
    </div>
  </div>


















  <div id="addRoom1" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
          <div class="modal-header">
            <h4 class="modal-title">Are you sure to print report for Tenant list</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
             <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
             <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
             <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
             <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
            <div class="form-group">
              <p class="text-warning"><small>This cannot be undone!</small></p>
            </div>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
            <input type="submit" onsubmit="setTimeout(function () { window.location.reload(); }, 10)" class="btn btn-danger" data-dismiss="static" name ="PrintTenant" value="Print">
          </div>
        </form>
      </div>
    </div>
  </div>







  <div id="addRoom2" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
          <div class="modal-header">
            <h4 class="modal-title">Are you sure to print report for Transient list</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
             <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
             <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
             <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
             <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
            <div class="form-group">
              <p class="text-warning"><small>This cannot be undone!</small></p>
            </div>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
            <input type="submit" onsubmit="setTimeout(function () { window.location.reload(); }, 10)" class="btn btn-danger" data-dismiss="static" name ="PrintTransient" value="Print">
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- page-content" -->
</div>

<!-- page-wrapper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html>
