<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    header("location:login.php");
    exit;
}

$servername = 'localhost';
$username = 'root';
$password = "";
$database = "plantcare"; 
$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die('Cannot connect to the database');
}

$plants = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_reminder']) && isset($_POST['plant_id'])) {
        $plantId = intval($_POST['plant_id']);
        $userId = intval($_SESSION['user_id']);

        $check = mysqli_query($conn, "SELECT * FROM reminders WHERE user_id=$userId AND plant_id=$plantId");
        if (mysqli_num_rows($check) === 0) {

            $plantRes = mysqli_query($conn, "SELECT common_plant, watering FROM plantdetails WHERE id=$plantId");
            $plantRow = mysqli_fetch_assoc($plantRes);
            $plantName = $plantRow['common_plant'];
            $wateringFrequency = strtolower($plantRow['watering']);

            $daysToAdd = 1;
            if (strpos($wateringFrequency, 'daily') !== false) {
                $daysToAdd = 1;
            } elseif (strpos($wateringFrequency, 'once every 2 days') !== false) {
                $daysToAdd = 2;
            } elseif (strpos($wateringFrequency, 'twice a week') !== false) {
                $daysToAdd = 4;
            } elseif (strpos($wateringFrequency, 'once a week') !== false) {
                $daysToAdd = 7;
            } elseif (strpos($wateringFrequency, 'once every 2 weeks') !== false) {
                $daysToAdd = 14;
            }

            $nextWateringDate = date('Y-m-d', strtotime("+$daysToAdd days"));

            mysqli_query($conn, "INSERT INTO reminders (user_id, plant_id, next_watering_date) VALUES ($userId, $plantId, '$nextWateringDate')");
            $message = "Added to reminders!";

            $emailRes = mysqli_query($conn, "SELECT user_mail FROM userdetails WHERE user_id=$userId");
            $emailRow = mysqli_fetch_assoc($emailRes);
            $userEmail = $emailRow['user_mail'];

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = '22b01a4256@svecw.edu.in';    
                $mail->Password   = 'wxdqtjujnkuulpod';     
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('your_email@gmail.com', 'PlantCare Reminder');
                $mail->addAddress($userEmail);

                $mail->Subject = "🌿 Reminder set for $plantName!";
                $mail->Body    = "Hello " . $_SESSION['name'] . ",\n\nYou've set a reminder to care for your plant: $plantName.\nNext watering is on: $nextWateringDate.";

                $mail->send();
            } catch (Exception $e) {
                // Silent fail
            }

        } else {
            $message = "Already in reminders!";
        }
    }

    if (isset($_POST['searchbox']) && !empty(trim($_POST['searchbox']))) {
        $name = mysqli_real_escape_string($conn, trim($_POST['searchbox']));
        $un = "SELECT * FROM `plantdetails` WHERE `common_plant` = '$name'";
        $result1 = mysqli_query($conn, $un);

        if (mysqli_num_rows($result1) > 0) {
            $searchResult = mysqli_fetch_assoc($result1);
        } else {
            $searchResult = "No results found for '$name'.";
        }
    }
}

$plantsQuery = "SELECT * FROM `plantdetails`";
$plantsResult = mysqli_query($conn, $plantsQuery);
if ($plantsResult && mysqli_num_rows($plantsResult) > 0) {
    while ($row = mysqli_fetch_assoc($plantsResult)) {
        $plants[] = $row;
    }
}
?>

<head>
    <link rel="stylesheet" href="style.css" />
</head>

<nav class="navbar">
    <div class="logo">
        <a href="http://localhost/PlantCare/index.php"><?php echo $_SESSION['name']; ?></a>
    </div>
    <button class="nav-toggle" id="navToggle">&#9776;</button>
    <ul class="nav-links" id="navLinks">
        <li><a href="http://localhost/PlantCare/index.php">Home</a></li>
        <li><a href="http://localhost/PlantCare/plantinfo.php">Plant Info</a></li>
        <li><a href="http://localhost/PlantCare/reminders.php">Reminders</a></li>
        <li><a href="http://localhost/PlantCare/logout.php">Logout</a></li>
    </ul>
</nav>

<script>
    document.getElementById('navToggle').addEventListener('click', function () {
        document.getElementById('navLinks').classList.toggle('active');
    });
</script>

<section id="searchsection">
    <h1>Search for plant information</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="search" name="searchbox" id="searchbox" placeholder="Search for any plant" />
        <input type="submit" id="sbutton" value="Search" />
    </form>

    <?php if (isset($searchResult)): ?>
        <?php if (is_array($searchResult)): ?>
            <div class="card">
                <h3>Name: <i><?php echo htmlspecialchars($searchResult['common_plant']); ?></i></h3>
                <div><strong>Scientific Name: </strong><i><?php echo htmlspecialchars($searchResult['scientific_name']); ?></i></div>
                <div><strong>Watering: </strong><i><?php echo htmlspecialchars($searchResult['watering']); ?></i></div>
                <div><strong>Sunlight: </strong><i><?php echo htmlspecialchars($searchResult['sunlight']); ?></i></div>
                <div><strong>Poisonous: </strong><i><?php echo htmlspecialchars($searchResult['poisonous']); ?></i></div>
                <div><strong>Soil Needed: </strong><i><?php echo htmlspecialchars($searchResult['soil_needed']); ?></i></div>

                <form method="post" class="add-form">
                    <input type="hidden" name="plant_id" value="<?php echo $searchResult['id']; ?>" />
                    <button type="submit" name="add_reminder" class="add-button">Add to Reminders</button>
                </form>
                <?php if (isset($message)) : ?>
                    <p class="msg"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="no-results"><?php echo htmlspecialchars($searchResult); ?></div>
        <?php endif; ?>
    <?php endif; ?>
</section>

<h1>You may also like....</h1>
<div class="plantcards">
    <?php if (count($plants) > 0): ?>
        <?php foreach ($plants as $plant): ?>
            <div class="card1">
                <h3>Name: <i><?php echo htmlspecialchars($plant['common_plant']); ?></i></h3>
                <div><strong>Scientific Name: </strong><i><?php echo htmlspecialchars($plant['scientific_name']); ?></i></div>
                <div><strong>Soil Needed: </strong><i><?php echo htmlspecialchars($plant['soil_needed']); ?></i></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No other plants available to display.</p>
    <?php endif; ?>
</div>

<script>
    const toggleBtn = document.getElementById('homeimg');
    const navList = document.querySelector('#navbar ul');
    toggleBtn.addEventListener('click', () => {
        if (navList.style.display === 'block') {
            navList.style.display = 'none';
        } else {
            navList.style.display = 'block';
        }
    });
</script>
