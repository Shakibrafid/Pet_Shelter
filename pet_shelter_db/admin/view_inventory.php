<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");  
    exit();
}
include("../includes/db_connect.php"); 

// Query to fetch inventory details
$query = "SELECT * FROM inventory";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory List</title>
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

<div class="panel">
    <h1>Inventory List</h1>
    
    <?php
    // Check if there are items in the inventory
    if (mysqli_num_rows($result) > 0) {
        // Table to display inventory items
        echo "<table class='data-table'>";
        echo "<tr><th>Item Name</th><th>Quantity</th><th>Shelter ID</th><th>Actions</th></tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['item_name'] . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . $row['shelter_id'] . "</td>";
            echo "<td><a href='delete_inventory.php?item_id=" . $row['item_id'] . "' class='btn'>Delete</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No items in inventory.</p>";
    }
    ?>
</div>

<a class="btn" href="add_inventory.php">Add Inventory</a>
<a class="btn" href="dashboard.php">Back to Admin Dashboard</a>

</body>
</html>
