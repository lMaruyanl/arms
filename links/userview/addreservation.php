<?php
require_once "../config.php";


if($_SERVER["REQUEST_METHOD"] == "POST")
{
  if(isset($_POST['addR']))
  {
        $image = $_FILES['image']['name'];
  	// Get text

  	// image file directory
  	   $target = "links/userview/images/".basename($image);
        $paramguestname = "";
        $paramguestcontact = "";
        $paramguestemail = "";
        $paramguestime = "";
        $paramguestdate ="";
        $paramimage = "";
        $sqlsssss = "INSERT INTO reservation (Guest_Name, Contact, guest_email, date, time, status, image) VALUES (?, ?, ?, ?, ?, ?, ?)";

        if($stmt = $mysqli->prepare($sqlsssss)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssss", $paramguestname, $paramguestcontact, $paramguestemail, $paramguestime, $paramguestdate, $status, $paramimage);
            // Set parameters
            $paramguestname = $_POST['guestname'];
            $paramguestcontact = $_POST['guestcontact'];
            $paramguestemail = $_POST['email'];
            $paramguesttime = $_POST['time'];
            $paramguestdate = $_POST['date'];
            $status = "Pending";
            $paramimage =$image;
            
            if($stmt->execute()){
                // Redirect to login page
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                   $msg = "Image uploaded successfully";
                  }else{
                    $msg = "Failed to upload image";
                  }
                header("location: index.php");
            } else{
              echo "image".$paramimage;echo "this</br>";
            }


              $stmt->close();

        }
  $mysqli->close();

  }

}
 ?>
