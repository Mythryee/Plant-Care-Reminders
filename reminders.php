<?php
session_start();
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit;
}

$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'plantcare';
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) die("Database connection failed: " . mysqli_connect_error());

$userId = intval($_SESSION['user_id']);
$userEmail = "";

$userResult = mysqli_query($conn, "SELECT user_mail FROM userdetails WHERE user_id = $userId");
if (!$userResult) {
    die("Error fetching user email: " . mysqli_error($conn));
}

if ($userRow = mysqli_fetch_assoc($userResult)) {
    $userEmail = $userRow['user_mail'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reminder'])) {
    $reminderPlantId = intval($_POST['reminder_id']);
    mysqli_query($conn, "DELETE FROM reminders WHERE plant_id = $reminderPlantId AND user_id = $userId");
    header("Location: reminders.php");
    exit;
}

$reminders = [];
$result = mysqli_query($conn, "SELECT r.user_id as reminder_id, p.* FROM reminders r JOIN plantdetails p ON r.plant_id = p.id WHERE r.user_id = $userId");

if (!$result) {
    die("Error fetching reminders: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)) {
    $reminders[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>

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

<section id="reminder-section">
    <h1>Your Reminders</h1>

    <?php if (count($reminders) > 0): ?>
        <div class="plantcards">
            <?php foreach ($reminders as $plant): ?>
                <div class="card1">
                    <h3><?php echo htmlspecialchars($plant['common_plant']); ?></h3>
                    <p>Scientific: <?php echo htmlspecialchars($plant['scientific_name']); ?></p>
                    <p>Soil: <?php echo htmlspecialchars($plant['soil_needed']); ?></p>
                    <form method="post">
                        <input type="hidden" name="reminder_id" value="<?php echo $plant['id']; ?>">
                        <button type="submit" name="delete_reminder" class="add-button">Delete Reminder</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No reminders set yet.</p>
    <?php endif; ?>
</section>

</body>
</html>
