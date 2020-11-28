<?php
// Initialize the session
session_start();


// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      header("location: links/welcome.php");
    exit;
}

// Include config file
require_once "links/config.php";

// Define variables and initialize with empty values
$username = $password = $accounttype = $tentid = "";
$username_err = $password_err = "";

// Processing form data when form is submitted

if($_SERVER["REQUEST_METHOD"] == "POST"){
if(isset($_POST['login'])){
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password, accounttype, Tenant_Id FROM useraccount WHERE username = ?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1){
                    // Bind result variables
                    $stmt->bind_result($id, $username, $hashed_password, $accounttype, $tentid);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["Usertype"] = $accounttype;
                            $_SESSION["ttentantid"] = $tentid;
                            // Redirect user to welcome page
                            header("location: links/welcome.php");

                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                            echo "<script>setTimeout(function() { $('#ModalLogin').modal();}, 1000);</script>";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                    echo "<script>setTimeout(function() { $('#ModalLogin').modal();}, 1000);</script>";
                }
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Theme Made By www.w3schools.com -->
  <title>Apartment Rental Management System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="icon" href="icon/main.png">
  <style>
  @font-face {
    src: url(text/Anton-Regular.ttf);
    font-family: text2; 
  }
  @font-face {
    src: url(text/Roboto-Light.ttf);
    font-family: robo;
  }
  .map-container-7{
  overflow:hidden;
  padding-bottom:56.25%;
  position:relative;
  height:0;
  }
  .map-container-7 iframe{
  left:0;
  top:0;
  height:100%;
  width:100%;
  position:absolute;
  }

  body {
      font: 400 15px Lato, sans-serif;
      line-height: 1.8;
      color: #818181;
      background-color: whitesmoke;
  }
  .wrapper{ width: 350px; padding: 20px; }
  h2 {
      font-size: 24px;
      text-transform: uppercase;
      color: #303030;
      font-weight: 600;
      margin-bottom: 30px;
  }
  h4 {
      font-size: 19px;
      line-height: 1.375em;
      color: #303030;
      font-weight: 400;
      margin-bottom: 30px;
  }
  .jumbotrontext{
      background-image: url(bg2.jpg);
      background-size: 100%;
      height: 660px;
      background-position: center;
      color: #fff;

      display: flex;
  }
  .jumbotrontext1{
    display: flex;
    margin-left:100px;
    margin-top:100px;
  }
  .jumbotrontext2{
    display: flex;
    margin-top:0;
    margin-left: 50px;
  }
  .container-fluid {
      padding: 60px 50px;
  }
  .bg-grey {
      background-color: #f6f6f6;
  }
  .logo-small {
      margin:auto;
      color: #006ad7;
      font-size: 75px;
  }
  .logo {
      color: #006ad7;
      font-size: 200px;
  }
  .thumbnail {
      padding: 0 0 15px 0;
      border: none;
      border-radius: 0;
  }
  .thumbnail img {
      width: 100%;
      height: 100%;
      margin-bottom: 10px;
  }
  .carousel-control.right, .carousel-control.left {
      background-image: none;
      color: #006ad7;
  }
  .carousel-indicators li {
      border-color: #006ad7;
  }
  .carousel-indicators li.active {
      background-color: #006ad7;
  }
  .item h4 {
      font-size: 19px;
      line-height: 1.375em;
      font-weight: 400;
      font-style: italic;
      margin: 70px 0;
  }
  .item span {
      font-style: normal;
  }
  .panel {
      border: 1px solid #006ad7;
      border-radius:0 !important;
      transition: box-shadow 0.5s;
  }
  .panel:hover {
      box-shadow: 5px 0px 40px rgba(0,0,0, .2);
  }
  .panel-footer .btn:hover {
      border: 1px solid #006ad7;
      background-color: #fff !important;
      color: #006ad7;
  }
  .panel-heading {
      color: #fff !important;
      background-color: #006ad7 !important;
      padding: 25px;
      border-bottom: 1px solid transparent;
      border-top-left-radius: 0px;
      border-top-right-radius: 0px;
      border-bottom-left-radius: 0px;
      border-bottom-right-radius: 0px;
  }
  .panel-footer {
      background-color: white !important;
  }
  .panel-footer h3 {
      font-size: 32px;
  }
  .panel-footer h4 {
      color: #aaa;
      font-size: 14px;
  }
  .panel-footer .btn {
      margin: 15px 0;
      background-color: #006ad7;
      color: #fff;
  }
  .navbar {
      margin-bottom: 0;
      background-color: #b2beb5;
      z-index: 9999;
      border: 0;
      font-size: 12px !important;
      line-height: 1.42857143 !important;
      letter-spacing: 4px;
      border-radius: 0;
      font-family: Montserrat, sans-serif;
  }
  .navbar li a, .navbar .navbar-brand {
      color: black !important;
  }
  .navbar-nav li a:hover, .navbar-nav li.active a {
      color: #006ad7 !important;
      background-color: #fff !important;
  }
  .navbar-default .navbar-toggle {
      border-color: transparent;
      color: #fff !important;
  }
  footer .glyphicon {
      font-size: 20px;
      margin-bottom: 20px;
      color: #006ad7;
  }
  .slideanim {visibility:hidden;}
  .slide {
      animation-name: slide;
      -webkit-animation-name: slide;
      animation-duration: 1s;
      -webkit-animation-duration: 1s;
      visibility: visible;
  }
  .homepagetext1 {
    font-family:text2;
    font-weight: bold;
    font-size: 100px;
    background-color: darkblue;
    border-style: solid;
    border-color: white;
    text-align: center;
    color: whitesmoke;
    width: 200px;

}
.homepagetext2 {
    font-family:text2;
    font-weight: bold;
    font-size: 100px;
    background-color: yellowgreen;
    text-align: center;
    border-style: solid;
    border-color: white;
    color: whitesmoke;
    width: 200px;

}
.homepagetext3 {
   font-family:text2;
    font-weight: bold;
    font-size: 100px;
    background-color: yellowgreen;
    text-align: center;
    border-style: solid;
    border-color: white;
    color: whitesmoke;
    width: 300px;

}
.homepagetext4 {
    font-family:text2;
    font-weight: bold;
    font-size: 100px;
    background-color: darkblue;
    text-align: center;
    border-style: solid;
    border-color: white;
    color: whitesmoke;
    width: 300px;
}
  @keyframes slide {
    0% {
      opacity: 0;
      transform: translateY(70%);
    }
    100% {
      opacity: 1;
      transform: translateY(0%);
    }
  }
  @-webkit-keyframes slide {
    0% {
      opacity: 0;
      -webkit-transform: translateY(70%);
    }
    100% {
      opacity: 1;
      -webkit-transform: translateY(0%);
    }
  }
  .col-sm-4 {
      height:150px;
    }
  @media screen and (max-width: 768px) {
    .col-sm-4 {
      text-align: center;
      margin: 25px 0;
      height:auto;
    }
    .btn-lg {
        width: 100%;
        margin-bottom: 35px;
    }

    .jumbotrontext {
      background-image: none;
      background-color: blue;
     }
     .jumbotrontext1{
     margin:auto;
    }
    .jumbotrontext2{
     margin:auto;
    }
     .homepagetext1 {
      font-size: 50px;
      width: 150px;
    
     }
     .homepagetext2 {
      font-size: 50px;
      width: 150px;
      
     }

    .homepagetext3 {
       font-size: 50px;
      width: 150px;
      
      }
    .homepagetext4 {
     font-size: 50px;
      width: 150px;
   
      }
  }
  @media screen and (max-width: 480px) {
    .logo {
        font-size: 150px;
    }
    .jumbotron {
      background-image: none;
      background-color: blue;
     }
     .homepagetext1 {
      font-size: 50px;
      width: 150px;
    
     }
     .homepagetext2 {
      font-size: 50px;
      width: 150px;
      
     }

    .homepagetext3 {
       font-size: 50px;
      width: 150px;
      
      }
    .homepagetext4 {
     font-size: 50px;
      width: 150px;
   
      }
  }
    }
  }
  .map-container-7{
overflow:hidden;
padding-bottom:56.25%;
position:relative;
height:0;
}
.map-container-7 iframe{
left:0;
top:0;
height:100%;
width:100%;
position:absolute;
}
#loga{
  color: #FFFFFF;
}
#loga, .icon-size{
  top: 7px;
  left: 5px;
   font-size:40px;

}
.building_text{
  float: left;
  width:100%;
  height:auto;
}
.building_text div{
  margin: 0 50px;
  text-align:center;
}
.building_test p{
  margin: 0;
}
.menu {
  width:80%;
  height:500px;
  margin:auto;
}
html{
  Scroll-behavior:smooth;
}
.amme{
  font-size:15px;
}
  </style>
</head>
<body id="ARMS" data-spy="scroll" data-target=".navbar" data-offset="60">

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-left">
      <li><a href="#">
          <span class="glyphicon glyphicon-home"></span> ARMS
        </a></li>
        <li><a href="#ARMS">ABOUT</a></li>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#locations">
          Locations <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
              <li><a href="#mylocation1">Mandaluyong City</a></li>
              <li><a href="#mylocation2">Makati City</a></li>
              <li><a href="#mylocation3">Pasig City</a></li>                        
          </ul>
         </li>
        <li><a href="#contact">Contact Us</a></li>
        <li><a href="#reservation" data-toggle="modal">Reservation</a></li>
        <li><a data-toggle="modal" data-target="#ModalLogin">LOGIN</a></li>


      </ul>
      
    </div>
  </div>
  <div class="modal fade" id="ModalLogin" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Login</h4>
          </div>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" name = "loginform">

          <div class="modal-body">
            <div class="Container-fluid text-center">
                <p>Please fill in your credentials to login.</p>

                      <div class="row">
                        <div class="col-sm-12 form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                          <input class="form-control" name="username" placeholder="Username" value="<?php echo $username; ?>" type="text" required>
                          <span class="help-block"><?php echo $username_err; ?></span>
                        </div>
                      </br>
                        <div class="col-sm-12 form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                          <input class="form-control" id="myInput"name="password" placeholder="Password" type="password" required>
                          <span class="help-block"><?php echo $password_err; ?></span>
                          <input type="checkbox"onclick="myFunctionCh()" class="custom-control-input" id="defaultUnchecked">
                          <label class="custom-control-label" for="defaultUnchecked">Show Password</label>
                        </div>
                        <script>
                          function myFunctionCh() {
                          var x = document.getElementById("myInput");
                          if (x.type === "password") {
                            x.type = "text";
                          } else {
                            x.type = "password";
                          }
                          }
                          </script>

                      </div>


            </div>

          </div>
          <div class="modal-footer">
          <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
          <button class="btn btn-primary pull-right" type="submit" value= "login" name="login">Login</button>
          </div>
            </form>
        </div>
      </div>
    </div>





  <div class="modal fade" id="ModalMessageSuccess" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Success</h4>
        </div>
        <div class="modal-body">
          <p>Feedback sent successfully!</p>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="ModalMessageNotSuccess" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">unsuccessful</h4>
        </div>
        <div class="modal-body">
          <p>Feedback sent error!</p>
          <p>There was an error sending your feedback.</p>
        </div>
      </div>
    </div>
  </div>
</nav>

<div class="jumbotrontext">
  <div>  
        <div class="jumbotrontext1">
            <p class ="homepagetext1">
                Find
            </p>
            <p class ="homepagetext2">
                Your
            </p>
        </div>
        <div class="jumbotrontext2">
            <p class = "homepagetext3">
                Rental
            </p>
            <p class ="homepagetext4">
                Here
            </p>
        </div>              
  </div>
</div>
<!--<div id="locations" class="menu">
<br></br>
  <div>
    <h2>Locations of buildings</h2>
  </div>
  <div>
    <a href="#mylocation1">location 1</a>
  </div>
  <div>
    <a href="#mylocation2">location 2</a>
  </div>
  <div>
    <a href="#mylocation3">location 3</a>
  </div>
</div>-->
<br></br>
<!-- location1 -->
  <div id="mylocation1" class="carousel slide" data-ride="carousel" style="top:-29px;">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#mylocation1" data-slide-to="0" class="active"></li>
      <li data-target="#mylocation1" data-slide-to="1"></li>
      <li data-target="#mylocation1" data-slide-to="2"></li>
      <li data-target="#mylocation1" data-slide-to="3"></li>
      <li data-target="#mylocation1" data-slide-to="4"></li>
    </ol>
    <!-- Wrapper for slides -->
    <div class="carousel-inner">

      <div class="item active">
        <img src="Carouselpic\location1.JPG" alt="first pic" style="width:auto; height:500px; margin:auto;">
      </div>
      <div class="item">
        <img src="Carouselpic\location1\building1room1.JPG" alt="Second pic" style="width:auto; height:500px; margin:auto;">
      </div>
      <div class="item">
        <img src="Carouselpic\location1\building1room2.JPG" alt="third pic"  style="width:auto; height:500px; margin:auto;">
      </div>
      <div class="item">
        <img src="Carouselpic\location1\building1room3.JPG" alt="Fourth pic" style="width:auto; height:500px; margin:auto;">
      </div>
      <div class="item">
        <img src="Carouselpic\location1\building1room4.jpeg" alt="Fifth pic" style="width:auto; height:500px; margin:auto;">
      </div>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#mylocation1" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#mylocation1" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  <!-- location1 details -->
<div class="building_text">
  <div>
      <h1> Location 1  Building Details</h1>
  </div>
  <div>
     <p>Address: 5.P.Cruz St Barangka ibaba Mandaluyong city</p>
     <p>Income Requirment: Must have 2.5x the rent in the total household income</p>
     <p>Pets: Cats and Dogs are allowed</p>
     <p>Fee: $300 per aparment</p>
     <p>Rent: $25 for 1 per/month, $40 for 2pets/months</p>
  </div>
</div>
<br></br>

<!-- Container (Services Section) -->
<div id="services" class="container-fluid text-center">
  <h2>Location 1 AMMENITIES</h2>
  <h4>What we offer</h4>
  <br>
  <div class="row slideanim">
    <div class="col-sm-4" style="display:flex;">
      <span class="glyphicon glyphicon-globe logo-small" style="display:flex;">
      </span>
      <div style="margin-left:10px;">
      <h4 style="font-size:30px; margin-bottom:10px; font-farmily:robo;">FREE WIFI</h4>
      <p class="amme">The building is a facility with a free wifi Access with an internet speed that matched up the standard data rate speed for researching</p>
      </div>
    </div>
    <div class="col-sm-4" style="display:flex;">
      <span class="glyphicon glyphicon-facetime-video logo-small" style="display:flex;">
      </span>
      <div style="margin-left:10px;">
      <h4 style="font-size:30px; margin-bottom:10px; font-farmily:robo;">Cover with CCTV</h4>
      <p class="amme">The building is a facility Covered with Cameras in every corner and hallway.</p>
      </div>
    </div>
    <div class="col-sm-4" style="display:flex;">
      <span class="glyphicon glyphicon-road logo-small" style="display:flex;">
      </span>
      <div style="margin-left:10px;">
      <h4 style="font-size:30px; margin-bottom:10px; font-farmily:robo;">Free Parking</h4>
      <p class="amme">The building has a large space for tenants with vehicle.</p>
      </div>
    </div>
  </div>
</div>
<br>

<!-- location2 -->

<div id="mylocation2" class="carousel slide" data-ride="carousel" style="top:-29px;">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#mylocation2" data-slide-to="0" class="active"></li>
      <li data-target="#mylocation2" data-slide-to="1"></li>
      <li data-target="#mylocation2" data-slide-to="2"></li>
      <li data-target="#mylocation2" data-slide-to="3"></li>
      <li data-target="#mylocation2" data-slide-to="4"></li>
    </ol>
    <!-- Wrapper for slides -->
    <div class="carousel-inner">

      <div class="item active">
        <img src="Carouselpic\location2.JPG" alt="first pic" style="width:auto; height:500px; margin:auto;">
      </div>
      <div class="item">
        <img src="Carouselpic\location1\building1room1.JPG" alt="Second pic" style="width:auto; height:500px; margin:auto;">
      </div>
      <div class="item">
        <img src="Carouselpic\location1\building1room2.JPG" alt="third pic"  style="width:auto; height:500px; margin:auto;">
      </div>
      <div class="item">
        <img src="Carouselpic\location1\building1room3.JPG" alt="Fourth pic" style="width:auto; height:500px; margin:auto;">
      </div>
      <div class="item">
        <img src="Carouselpic\location1\building1room4.jpeg" alt="Fifth pic" style="width:auto; height:500px; margin:auto;">
      </div>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#mylocation2" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#mylocation2" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  <!-- location1 details -->
<div class="building_text">
  <div>
      <h1> Location 2  Building Details</h1>
  </div>
  <div>
     <p>Address: Makati city</p>
     <p>Income Requirment: Must have 1.5x the rent in the total household income</p>
     <p>Pets: Dogs are allowed</p>
     <p>Fee: $250 per aparment</p>
     <p>Rent: $500 for 1 per/month, $40 for 2pets/months</p>
  </div>
</div>

<!-- Container (Services Section) -->
<div id="services" class="container-fluid text-center">
  <h2>Location 2 AMMENITIES</h2>
  <h4>What we offer</h4>
  <br>
  <div class="row slideanim">
    <div class="col-sm-4">
      <span class="glyphicon glyphicon-flash logo-small"></span>
      <h4>ELECTRICITY</h4>
      <p>In order to measure exact amount for electricity bill, we offer submettered electricity</p>
    </div>
    <div class="col-sm-4">
      <span class="glyphicon glyphicon-leaf logo-small"></span>
      <h4>LAUNDRY WORKS</h4>
      <p>As part of necessity and self-sanitary, tenants will be allowed to do laundry works inside the facility.</p>
    </div>
    <div class="col-sm-4">
      <span class="glyphicon glyphicon-tint logo-small"></span>
      <h4>FREE WATER</h4>
      <p>In one drop of water are found all the secrets of all the oceans; in one aspect of You are found all the aspects of existence. That's why it's free </p>
    </div>
  </div>
  <br><br>
</div>
<!-- Reservation -->
<!--<a href="#reservation" class="btn btn-primary" style="background-color:transparent;border-left:none;border-right:none;border-top:none; border-bottom:none;" data-toggle="modal"><span class="fas fa-plus-circle" style="color:#2196F3"></span><span style="color:#2196F3;"> Create one</span></a>-->
<div id="reservation" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="post" action="addreservation.php" enctype="multipart/form-data">
            <div class="modal-header">
            <br></br>
              <h4 class="modal-title">Reservation Form:</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">

              <div class="form-group">
                <label>Guest Full Name <span class="badge badge-info">Format("Letters Only")</span></label>
                <input name="name" class="form-control" pattern="[A-Za-z ]+" value="<?php echo $TenantRES['Guardian_Name']; ?>">
              </div>
              <div class="form-group">
                <label>Guest Phone Number <span class="badge badge-info">Format (xxx-xxx-xxxx)</span></label>
                  <input type="tel" name="contact" pattern="^\d{3}-\d{3}-\d{4}$" class="form-control" value="<?php echo $TenantRES['Tenant_Contact']; ?>"required >
              </div>
              <div class="form-group">
              <label for="email">Enter your email:</label>
              <input type="email" id="email" name="email" class="form-control">
              </div>
              <div class="form-group">
                <label>Scheduled appointment <span class="badge badge-warning">Month/Day/Year</span></label>
                <input value = "<?php echo $val['Date_Started']; ?>" class="form-control" type="date" name="date"  min="<?php $today11 = date('Y-m-d'); echo $today11 ?>">
              </div>
              <div class="form-group">
              <label for="appt">Choose a time for your meeting:</label>
                <input type="time" id="time" name="time"
                 min="09:00" max="18:00" required>
                <small>Office hours are 9am to 6pm</small>
              </div>
              <div class="form-groupd">
              <p><input type="checkbox" required name="terms"> I accept the <a href="#terms" data-toggle="modal"><u>Terms and Conditions</u></a></p></div>
              <div class="form-group" align="center">
                <input type="hidden" name="size" value="1000000">
                  <label class="btn btn-default"  style="background-color:transparent;border-left:none;border-right:none;border-top:none; border-bottom:none;">
                  <label for="appt">Please Upload image of any valid ID:</label>
                      <span class="fas fa-upload" style="color:#2196F3"></span><span style="color:#2196F3;"></span><input type="file" name="image" hidden required>
                  </label>
              </div>
            </div>
            <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
              <input type="submit" onsubmit="setTimeout(function () { window.location.reload(); }, 10)" class="btn btn-danger" data-dismiss="static" name ="reservation" value="Apply">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<!-- Terms and conditions -->
<div id="terms" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <br></br>
              <h4 class="modal-title">Terms And Conditions:</h4>
            </div>
            <div class="modal-body">
            <p> This Rental/Lease Agreement (this “Contract") is made this [fill in day of month] day of [name
              of month], [year], by and between [Landlord/Apartment Community/Property Management
              Company Name] located at [City], [State] and [Tenant name], located at [City], [State]. Each
              Tenant is accountable for payment of rent. This is in conjunction with all terms of this Contract
              Agreement, including an addendum.</p><br>
            <p>1. Property. The leased dwelling is located at [City], [State] (and will be otherwise known as the
              “Apartment").</p><br>
            <p>2. Arrangement to Lease. The Property Management Company/Landlord is in agreement to
              lease to the Tenant. The Tenant is in agreement to lease from the Property Management
              Company/Landlord, the Apartment, according to the terms and conditions cited in this Contract
              Agreement.</p><br>
            <p>3. Term. This Lease will be for a term of [number] months beginning on [Lease start date] and
              ending on [Lease end date] (the “Long Term").
              Alternatively, this Lease will be for a shorter Term duration beginning on [Start date] and ending
             on [End date] (the “ Short Term").</p><br>
            <p>4. Rent. The Tenant will pay the Property Management Company/Landlord a monthly rent of
              [Rent amount]. The rent can be paid in advance and/or is due on the [Date of month] of each
              month as obligated by the designated and agreed upon Term.</p><br>
            <p>5. Payment will be made directly to the Property Management Company/Landlord at the address
              stated above (or at a designated online website portal that has been directed by the Property
              Management Company for residents of said property). Payment can be made using a debit card
              or credit card for an additional fee of [Additional fee amount]. It can also be paid by mail or in
              person by check in the Property Management Office of the Landlord.</p><br>
            <p>6. Additional Monies. Under this Agreement there could be situations where the Tenant may be
              notified to pay extra monies to the Property Management Company/Landlord. These charges will
              be in addition to the rent under this Contract Agreement and therefore will be paid upon the
              upcoming regularly scheduled rent due date.</p><br>
              If the Tenant fails to pay rent, the Tenant will pay a late charge in the amount of [Late fee
              amount, usually a percentage] of the monthly rent, and will be known as “Additional Rent." 
            <p>7. Use of Property. The Tenant will only occupy the Apartment. Or, also with his/her/their
              immediate family only. Or, also with his/her/their pet(s). The Apartment will only be used only
              for residential purposes. No illegal activities are allowed on the Apartment.</p><br>
            <p>8. Apartment Possession. If the Property Management Company does not give possession of
              the dwelling to the Tenant on the start date of the Term, the Tenant is not liable for the rent
              starting on the start date, but only after possession of the Apartment is given to the Tenant. Rent
              will be pro-rated.</p><br>
            <p>9. Security Deposit. When the Tenant signs this Contract Agreement, the Tenant will pay a
              security deposit in the amount of [Security deposit amount] to the [Landlord/Apartment
              Community/Property Management Company Name]. The security deposit will be retained by the
              Property Management Company/Landlord as security for the Tenant's fulfillment of obligations
              under this Contract Agreement.</p><br>
              If the Tenant does not abide with the Terms of this Contract Agreement, the Property
              Management Company/Landlord may apply the security deposit in payment of any amount owed
              for damages incurred and costs incurred by the Property Management Company/Landlord due to
              the Tenant's inability to comply to signed obligations of this Contract Agreement.
              The Property Management Company/Landlord will provide a written notice to the Tenant that
              references use of any or all of the security deposit. The Tenant will, within [Number of days]
              days following receipt of the notice, make a payment directly to the Landlord in an amount equal
              to that used by the Property Management Company/Landlord in order to reinstate the security
              deposit to its full monetary value for the remainder of the Tenant's occupancy of the Apartment.</p><br>
                        </div>
                        <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
            </div>
        </div>
      </div>
    </div>
  </div>
<!-- Container (Portfolio Section) -->


<section class="section">
  <h2 class="text-center">Contact us</h2>
  <!--<div class="card">
    <div class="card-body">
      <div id="map-container-google-12" class="z-depth-1-half map-container-7" style="height: 200px">
        <iframe src="https://maps.google.com/maps?q=412%20Barangka%20Dr.%2C%20Mandaluyong%2C%201550%20Kalakhang%20Maynila&t=k&z=19&ie=UTF8&iwloc=&output=embed" frameborder="0"
          style="border:0" allowfullscreen></iframe>
      </div>
    </div>
  </div>-->

</br>
<div id="contact" class="container-fluid">
  <div class="row">
    <div class="col-sm-5">
      <p>Contact us and we'll get back to you within 24 hours.</p>
      <p><span class="glyphicon glyphicon-map-marker"></span> Location of HQ </p>
      <p><span class="glyphicon glyphicon-phone"></span> +639484143467 / +639480704776</p>
      <p><span class="glyphicon glyphicon-envelope"></span> Apartment_System@gmail.com</p>
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="form" method="post" name="form">
    <div class="col-sm-7 slideanim">
      <div class="row">
        <div class="col-sm-6 form-group">
          <input class="form-control" id="name" name="name" placeholder="Name" type="text" required>
        </div>
        <div class="col-sm-6 form-group">
          <input class="form-control" id="email" name="email" placeholder="Email" type="email" required>
        </div>
      </div>
      <textarea class="form-control" id="comments" name="comments" placeholder="Comment" rows="5" ></textarea><br>
      <div class="row">
        <div class="col-sm-12 form-group">
          <button class="btn btn-default pull-right" type="submit" name="send">Send</button>
        </div>
      </div>
    </div>
  </form>
  </div>
  </div>
</section>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/OAuth.php';

if(isset($_POST['send']))
{
$email = $_POST['email'];
$name = $_POST['name'];
$comments = $_POST['comments'];
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = "smtp";
$mail->SMTPDebug  = 3;
$mail->SMTPAuth   = true;
$mail->SMTPSecure = "tls";
$mail->Port       = 587;
$mail->Host       = "smtp.gmail.com";
$mail->Username   = "ramonadormitory@gmail.com";
$mail->Password   = "Ramona123456";
$mail->IsHTML(true);
$mail->AddAddress("ramonadormitory@gmail.com", "Ramona");
$mail->SetFrom($email, $name);
$mail->AddReplyTo($email, $name);
$mail->Subject = "This is a Feedback from the Homepage";
$content = "<b> ". $comments ."</b>";
$mail->MsgHTML($content);

  if(!$mail->Send()) {
    echo "<script>$('#ModalMessageNotSuccess').modal('show')</script>";
    var_dump($mail);
  } else {
  echo "<script>$('#ModalMessageSuccess').modal('show')</script>";
  }

  }
  ?>
<footer class="container-fluid text-center">

      <a href="#ramonadormitory" title="To Top">
        <span class="glyphicon glyphicon-chevron-up"></span>
      </a>
</footer>

<script>
$(document).ready(function(){
  // Add smooth scrolling to all links in navbar + footer link
  //$(".navbar a, footer a[href='#myPage']").on('click', function(event) {

    // Prevent default anchor click behavior
    //event.preventDefault();

    // Store hash
    //var hash = this.hash;

    // Using jQuery's animate() method to add smooth page scroll
    // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
    //$('html, body').animate({
    //  scrollTop: $(hash).offset().top
   // }, 900, function(){

      // Add hash (#) to URL when done scrolling (default click behavior)
     // window.location.hash = hash;
   // });
//  });

  // Slide in elements on scroll
  $(window).scroll(function() {
    $(".slideanim").each(function(){
      var pos = $(this).offset().top;

      var winTop = $(window).scrollTop();
        if (pos < winTop + 600) {
          $(this).addClass("slide");
        }
    });
  });
})

</script>

</body>
</html>
