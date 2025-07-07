<?php
    include "Components/navbar.php";

?>
<head>
    <link rel="stylesheet" href="style.css">
</head>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Plant Care Signup</title>
  <link rel="stylesheet" href="plant-signup.css" />
</head>
<body>
  <section class="pc-signup-section">
    <div class="pc-signup-box">
      <h2>Create Your Plant Care Account</h2>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="pc_username" placeholder="Username" required />
        <input type="email" name="pc_email" placeholder="Email" required />
        <input type="password" name="pc_password" placeholder="Password" required />
        <input type="password" name="pc_confirm" placeholder="Confirm Password" required />
        <input type="submit" value="Signup" class="pc-signup-button" />
      </form>
    </div>
  </section>
</body>
</html>


<?php
    $servername = 'localhost';
    $username = 'root';
    $password = "";
    $database = "plantcare"; 
    $conn = mysqli_connect($servername,$username,$password,$database);
    if(!$conn){
        die('Cannot connect to the data base');
    }else{
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $username = $_POST['pc_username'];
            $mail = $_POST['pc_email'];
            $upass = $_POST['pc_password'];
            $cpass = $_POST['pc_confirm'];
            $uniquesql = "Select * from `userdetails` where `user_name`='$username'";
            $uniquesqlresult = mysqli_query($conn,$uniquesql);
            $noofrows = mysqli_num_rows($uniquesqlresult);
            if($noofrows > 0){
                echo '<script>alert("Username is already taken");</script>';
            }
            if (!preg_match("/^[a-zA-Z0-9]{8,}$/", $username)) {
                echo '<script>alert("Username must be at least 8 characters long.");</script>';
                exit();
            }


            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                echo '<script>alert("Invalid email format.");</script>';
                exit();
            }

            if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/", $upass)) {
                echo '<script>alert("Password must contain at least one letter, one number, and one special character, and be at least 8 characters long.");</script>';
                exit();
            }
            else{
                if($cpass == $upass){
                    $sql = "INSERT INTO `userdetails` ( `user_name`, `user_mail`, `user_pass`) VALUES ( '$username', '$mail', '$upass')";
                        $result = mysqli_query($conn,$sql);
                    if(!$result){
                        echo '<script>alert("You form is not submitted");</script>';
                    }else{
                        echo '<script>alert("You form is successfully submitted");</script>';
                    }
                }else{
                    echo '<script>alert("Your passwords doesnot match");</script>';
                }
            }
        }
        
    }
?>