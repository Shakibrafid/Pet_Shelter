<?php
session_start();  // Starts the session

// Checks if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");  // Redirect to login if not admin
    exit();  // Stops further execution
}


include("../includes/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gets the values from the form
    $name = $_POST['name'];
    $location = $_POST['location'];
    $capacity = $_POST['capacity'];
    $contact_number = $_POST['contact_number'];
    

    // Inserts new shelter into the database
    $sql = "INSERT INTO shelters (name, location, capacity, contact_number) 
            VALUES ('$name', '$location', '$capacity', '$contact_number')";

    if (mysqli_query($conn, $sql)) {
        echo "New shelter added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pets</title>
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
    <form action="add_shelter.php" method="POST">
        <label for="name">Shelter Name:</label>
        <input type="text" name="name" required><br>
        
        <label for="location">Location:</label>
        <input type="text" name="location" required><br>
        
        <label for="capacity">Capacity:</label>
        <input type="number" name="capacity" required><br>
        
        <label for="contact_number">Contact Number:</label>
        <input type="text" name="contact_number" required><br>

        
        <input type="submit" name="submit" value="Add Shelter" class="btn">
    </form>
</div>

<a class="btn" href="dashboard.php">Back to Admin Dashboard</a>

</body>
</html>
