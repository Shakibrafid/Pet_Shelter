<?php
session_start();

// Checks if the user is logged in and is an adopter
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'adopter') {
    header("Location: ../login.php");  
    exit(); 
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adopter Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="form-container">
        <h2>Adopter Dashboard</h2>

        <p>Welcome, <?php echo $_SESSION["name"]; ?>!</p>
        <p>Your role is: <?php echo $_SESSION["role"]; ?></p>

        <div class="dashboard-links">
            <a class="dashboard-btn" href="view_pets.php">View Available Pets</a>
            <a href="adoption_application.php">Adopt Now</a>
            <a href="payment.php">Payment Form</a>
            <a class="dashboard-btn logout-btn" href="../logout.php">Logout</a>
        </div>
    </div>

</body>
</html>