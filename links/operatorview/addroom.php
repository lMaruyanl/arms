<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$roomnumber = $numberofoccapants = $numberofoccupantsForEdit = 0;
$roomnumber_err = $numberofoccupants_err = $numberofoccupants_err1ForEdit = $location = $location_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST['addR']))
  {
    // Validate username
    if(empty(trim($_POST["roomnumbers"]))){
        $roomnumber_err = "Please enter a Room_Number.";
    } else{
        // Prepare a select statement
        $sql = "SELECT roomnumber FROM room WHERE roomnumber = ? AND location =?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("is", $param_roomnumber, $param_location);

            // Set parameters
            $param_roomnumber = trim($_POST["roomnumbers"]);
            $param_location = trim($_POST['location']);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();

                if($stmt->num_rows == 1){
                    $roomnumber_err = "Room number is already On the list.";
                  echo "<script>alert('$roomnumber_err');
                  location='roommanagement.php';
                  </script> ";

                } else{
                    $roomnumber = trim($_POST["roomnumbers"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Validate password
    if(empty(trim($_POST["location"]))){
        $location_err = "Please Select a Location.";
    } else{
          $location = trim($_POST["location"]);
      }

    }

    // Check input errors before inserting in database
    if(empty($roomnumber_err) && empty($numberofbeds_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO room (location, roomnumber, number) VALUES (?, ?, ?)";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sii", $param_location, $param_roomnumber, $param_numberofoccupants);

            // Set parameters
            $param_location = $location;
            $param_roomnumber = $roomnumber; // Creates a password hash
            $param_numberofoccupants = 0;
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: roommanagement.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        // Close statement
        $stmt->close();
    }

    // Close connection
    $mysqli->close();
}
?>
