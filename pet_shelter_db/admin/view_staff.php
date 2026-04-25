<?php
session_start();  


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");  
    exit();
}


include("../includes/db_connect.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staffs</title>
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
// Fetching all staff members with their details
$sql = "SELECT s.staff_id, s.position, u.name, u.email, u.phone, s.hire_date
        FROM staff s
        JOIN users u ON s.user_id = u.user_id";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Display staff details
        echo "<div class='staff-card'>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "<p>Email: " . $row['email'] . "</p>";
        echo "<p>Phone: " . $row['phone'] . "</p>";
        echo "<p>Position: " . $row['position'] . "</p>";
        echo "<p>Hire Date: " . $row['hire_date'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No staff available.</p>";
}
?>
</div>

<a class="btn" href="dashboard.php">Back to Admin Dashboard</a>

</body>
</html>