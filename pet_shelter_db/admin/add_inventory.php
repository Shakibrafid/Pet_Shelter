<?php 
session_start();

// Ensure the user is logged in and has an 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); 
    exit();
}

include("../includes/db_connect.php"); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $shelter_id = $_POST['shelter_id'];

    // Inserts the new inventory item into the database
    $query = "INSERT INTO inventory (item_name, quantity, shelter_id) 
              VALUES ('$item_name', '$quantity', '$shelter_id')";

    if (mysqli_query($conn, $query)) {
        echo "<p>New item added successfully!</p>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Inventory</title>
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
    <h1>Add New Inventory Item</h1>

    <form method="POST" action="add_inventory.php">
        <label>Item Name:</label>
        <input type="text" name="item_name" required><br><br>

        <label>Quantity:</label>
        <input type="number" name="quantity" required><br><br>

        <label>Shelter ID:</label>
        <input type="number" name="shelter_id" required><br><br>

        <button type="submit" class="btn">Add Item</button>
    </form>
</div>

<a class="btn" href="dashboard.php">Back to Admin Dashboard</a>

</body>
</html>