<head>
    <link rel="stylesheet" href="../style.css">
</head>
<body> 
    <nav class="navbar">
        <div class="logo">
            <a href="http://localhost/PlantCare/index.php">Plant Care</a>
        </div>
        <button class="nav-toggle" id="navToggle">&#9776;</button>
        <ul class="nav-links" id="navLinks">
            <li><a href="http://localhost/PlantCare/index.php">Home</a></li>
            <li><a href="http://localhost/PlantCare/plantinfo.php">Plant Info</a></li>
            <li><a href="#reminders">Reminders</a></li>
            <li><a href="http://localhost/PlantCare/login.php">Login</a></li>
        </ul>
    </nav>
    <script>
        document.getElementById('navToggle').addEventListener('click', function () {
            document.getElementById('navLinks').classList.toggle('active');
        });
    </script>
</body>
