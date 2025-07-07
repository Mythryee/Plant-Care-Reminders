
<head>
    <link rel="stylesheet" href="style.css">
</head>
<?php
    include "Components/navbar.php";
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "plantcare";
    $conn = mysqli_connect($servername, $username, $password, $database);
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    } else {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['pc_username']) && isset($_POST['pc_password'])) {
                $name = $_POST['pc_username'];
                $pass = $_POST['pc_password'];

                $un = "SELECT * FROM `userdetails` WHERE `user_name` = '$name'";
                $result1 = mysqli_query($conn, $un);
                $noofrows = mysqli_num_rows($result1);

                if ($noofrows == 1) {
                    while ($row = mysqli_fetch_assoc($result1)) {
                        if ($pass == $row['user_pass']) {
                            $_SESSION['loggedin'] = true;
                            $_SESSION['name'] = $name;
                            $_SESSION['user_id'] = $row['user_id'];  
                            header("Location: index.php");
                            exit();
                        } else {
                            echo "<script>alert('Incorrect password! Please try again.')</script>";
                        }
                    }
                } else {
                    echo "<script>alert('No such user found! Please check your username.')</script>";
                }
            }
        }
    }
?>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<section class="pc-login-section">
    <div class="pc-login-box">
        <h2>Plant Care Login</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="pc_username" placeholder="Username" required />
            <input type="password" name="pc_password" placeholder="Password" required />
            <a href="http://localhost/PlantCare/signup.php" class="pc-register-link">Don't have an account? Sign up</a>
            <input type="submit" value="Login" class="pc-login-button" />
        </form>
    </div>
</section>
