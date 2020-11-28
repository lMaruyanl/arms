<?php  
if (isset($_POST['submit_signup'])) {
   
    require 'links/config.php';

    $username = $_POST['username'];
    $number = $_POST['tenant'];
    $password = $_POST['pwd'];
    $type = $_POST['usertype'];

    if (empty($username) || empty($email) || empty($password) || empty($passwordr) || empty($lastn) || empty($firstn)) {
        header("location: ../signup.php?error=emptyfields&first=$firstn&last=$lastn&email=$email&user=$username");
        exit();
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("location: ../signup.php?error=invalidemail&user=$username&first=$firstn&last=$lastn");
        exit();
    }
    else if (!preg_match("/^[a-zA-Z0-9]*$/",$username)) {
        header("location: ../signup.php?error=invalidusername&first=$firstn&last=$lastn&email=$email");
        exit();
    }
    else if ($password !== $passwordr) {
        header("location: ../signup.php?error=passwordcheck&first=$firstn&last=$lastn&email=$email&user=$username");
        exit();
    }
    else {
        
        $sql = "SELECT username FROM users WHERE username=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../signup.php?error=mysqli");
        exit();
        }
        else {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $resultcheck = mysqli_stmt_num_rows($stmt);
            if($resultcheck > 0){
                header("location: ../signup.php?error=username_already_taken&first=$firstn&last=$lastn&email=$email&user=$username");
                 exit();
            }
             else {
                 $sql = "INSERT INTO users (username, email, lastname, firstname, pwd, secretanswer) VALUES (?, ?, ?, ?, ?, ?)";
                 $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("location: ../signup.php?error=mysqli");
                      exit();
                      }
                 else {
                     $hasdedpwd = password_hash($password, PASSWORD_DEFAULT);
                     mysqli_stmt_bind_param($stmt, "ssssss", $username, $email, $lastn, $firstn, $hasdedpwd, $pet);
                     mysqli_stmt_execute($stmt);
                     header("location: ../signup.php?Sign_up=Complete");
                     exit();
                     }
                }
            }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
else {
    header("location: ../signup.php?Sign_up");
    exit();
}