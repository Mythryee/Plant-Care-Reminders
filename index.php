<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:login.php");
}
?>
<head>
    <link rel="stylesheet" href="style.css">
</head>

<nav class="navbar">
    <div class="logo">
        <a href="http://localhost/PlantCare/index.php"><?php echo $_SESSION['name'] ?></a>
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

<section class="home" id="home">
    <div class="home-content">
        <h1>Welcome to <span>Plant Care App</span></h1>
        <h2>Your Companion for Healthy, Happy Plants</h2>
        <p>
            Never forget to water, fertilize, or repot your plants again. Plant Care App helps you track care routines,
            set smart reminders, and learn personalized tips for each of your green friends. Whether you're a beginner
            or a seasoned plant parent, we make plant care simple and joyful.
        </p>
        <div class="privilege">
            <p>🌿 Smart care reminders tailored to your plants</p>
            <p>📅 Easy tracking of watering, fertilizing & more</p>
        </div>
    </div>
</section>

<section class="plant-info" id="plant-info">
    <div class="plant-info-content">
        <h2>Plant Information</h2>
        <p>
            Access detailed care instructions, ideal growing conditions, and fascinating facts for over
            <strong>700+ plants</strong>. From succulents to tropicals and herbs, our extensive Plant Information
            database helps you nurture your green friends with confidence and ease.
        </p>
        <a href="http://localhost/PlantCare/plantinfo.php" class="plant-info-link">Explore Plant Information</a>
    </div>
</section>

<section class="plant-info" id="reminders">
    <div class="plant-info-content">
        <h2>Reminders</h2>
        <p>
            Stay on top of your plant care routine with personalized reminders for watering, fertilizing, pruning,
            and repotting. Our smart notification system helps you give your plants the attention they need,
            exactly when they need it.
        </p>
        <a href="http://localhost/PlantCare/reminders.php" class="plant-info-link">View Reminders</a>
    </div>
</section>

<section class="plant-info" id="contact">
    <div class="plant-info-content">
        <h2>Contact Me</h2>
        <p>
            Have questions, feedback, or need help with your plants? I'm here to help! Reach out through the Contact
            page and I’ll get back to you as soon as possible. Let’s grow together—your green friends are in good hands.
        </p>
        <a href="http://localhost/PlantCare/signup.php" class="plant-info-link">Get in Touch</a>
    </div>
</section>

<footer class="site-footer">
    <p>&copy; 2025 Lakshmi Mythryee. All Rights Reserved.</p>
</footer>
