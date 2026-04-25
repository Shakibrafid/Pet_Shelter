<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "staff") {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="navbar">
        <div class="logo">Pet Shelter</div>
        <div class="menu-links">
            <a href="../index.php">Home</a>
            <a href="../pets.php">Pets</a>
            <a href="../shelters.php">Shelters</a>
            <a href="../vets.php">Vets</a>
            <a href="../donate.php">Donate</a>
            <a href="../volunteer.php">Volunteer</a>
            <a href="../login.php">Login</a>
            <a href="../register.php">Register</a>
        </div>
    </div>

    <div class="form-container">
        <h2>Staff Dashboard</h2>
        <p>Welcome, <?php echo $_SESSION["name"]; ?>!</p>
        <p>Your role is: <?php echo $_SESSION["role"]; ?></p>
        <a href="view_pets.php">View Pets</a> |
        <a href="view_staff_assignments.php">View Assignments</a> |
        <a href="view_adoption_application.php">Adoption Applications</a> |
        <a href="add_vaccine.php">Vaccination</a> |
        <a href="add_medical_record.php">Pet Medical Record</a> |
        <a href="add_training_record.php">Pet Training Record</a> |
        <a href="add_rescue_report.php">Rescue Reports</a> |
        <a href="../logout.php">Logout</a>
    </div>
</body>
</html> 


