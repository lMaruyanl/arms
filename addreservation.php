<?php
require_once "links/config.php";


if($_SERVER["REQUEST_METHOD"] == "POST")
{
  if(isset($_POST['reservation']))
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
            $stmt->bind_param("sssssss", $paramguestname, $paramguestcontact, $paramguestemail, $paramguestdate, $paramguestime, $status, $paramimage);
            // Set parameters
            $paramguestname = $_POST['name'];
            $paramguestcontact = $_POST['contact'];
            $paramguestemail = $_POST['email'];
            $paramguestdate = $_POST['date'];
            $paramguestime = $_POST['time'];
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
              echo "image".$paramimage;echo "this wow error</br>";
            }


              $stmt->close();

        }
  $mysqli->close();

  }
  if(isset($_POST['editstatus']))
  {
    $occupantupdate = "UPDATE reservation SET status=?  where Guest_Id=?";  
    if($stmt = $mysqli->prepare($occupantupdate)){
      $stmt->bind_param("si",$occupants, $ID);
      $occupants = $_POST['editstatus'];
      $ID = $_POST['editguestid'];
        $stmt->execute();
        $stmt->close();
    }
    header("location: transient.php");
  }
}
 ?>
