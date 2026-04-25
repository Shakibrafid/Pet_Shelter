<?php
session_start();  

// Checks if the user is logged in and is a staff
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");  // Redirect to login if not staff
    exit();  // Stops further execution
}


include("../includes/db_connect.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Assignments</title>
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

<div class="card-grid">
<?php
// This query fetches all staff assignments with their details
$sql = "SELECT sa.assignment_id, u.name AS staff_name, p.name AS pet_name, sa.assigned_date
        FROM staff_assignments sa
        JOIN staff s ON sa.staff_id = s.staff_id
        JOIN users u ON s.user_id = u.user_id
        JOIN pets p ON sa.pet_id = p.pet_id";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Displays staff assignment details
        echo "<div class='assignment-card'>";
        echo "<h3>Staff: " . $row['staff_name'] . "</h3>";
        echo "<p>Assigned Pet: " . $row['pet_name'] . "</p>";
        echo "<p>Assigned Date: " . $row['assigned_date'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No staff assignments available.</p>";
}
?>
</div>

<a class="btn" href="dashboard.php">Back to Staff Dashboard</a>

</body>
</html>