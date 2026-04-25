<?php
session_start();  // Start the session

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");  // Redirect to login if not admin
    exit();  // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        <h2>Admin Dashboard</h2>
        <p>Welcome, <?php echo $_SESSION["name"]; ?>!</p>
        <p>Your role is: <?php echo $_SESSION["role"]; ?></p>
        <a href="view_pets.php">View Pets</a> | 
        <a href="add_pet.php">Add New Pet</a> | 
        <a href="view_staff.php">View Staff</a> | 
        <a href="view_staff_assignments.php">View Staff Assignments</a> | 
        <a href="view_inventory.php">View Inventory</a> |
        <a href="add_inventory.php">Add Inventory</a> |
        <a href="add_shelter.php">Add Shelter</a> |
        <a href="add_staff.php">Add Staff</a> |
        <a href="add_staff_assignment.php">Assign To Staff</a> |
        <a href="view_donations.php">View Donations</a> | 
        <a href="total_adoption.php">Adoptions</a> | 
        <a href="../logout.php">Logout</a>
    </div>
</body>
</html>