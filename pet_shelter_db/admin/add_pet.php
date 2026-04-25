<?php
session_start(); 

// Checks if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");  
    exit();
}

include("../includes/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gets the values from the form
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $color = $_POST['color'];
    $status = $_POST['status'];
    $arrival_date = $_POST['arrival_date'];
    $breed_id = $_POST['breed_id'];
    $species_id = $_POST['species_id'];
    $shelter_id = $_POST['shelter_id'];

    // Inserts new pet into the database
    $sql = "INSERT INTO pets (name, age, gender, color, status, arrival_date, breed_id, species_id, shelter_id) 
            VALUES ('$name', '$age', '$gender', '$color', '$status', '$arrival_date', '$breed_id', '$species_id', '$shelter_id')";

    if (mysqli_query($conn, $sql)) {
        echo "New pet added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<form action="add_pet.php" method="POST">
    <label for="name">Pet Name:</label>
    <input type="text" name="name" required><br>
    
    <label for="age">Age:</label>
    <input type="number" name="age" required><br>
    
    <label for="gender">Gender:</label>
    <input type="text" name="gender" required><br>
    
    <label for="color">Color:</label>
    <input type="text" name="color" required><br>
    
    <label for="breed_id">Breed:</label>
    <select name="breed_id" required>
        <?php
        $sql = "SELECT * FROM breeds";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['breed_id'] . "'>" . $row['breed_name'] . "</option>";
        }
        ?>
    </select><br>
    
    <label for="species_id">Species:</label>
    <select name="species_id" required>
        <?php
        $sql = "SELECT * FROM species";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['species_id'] . "'>" . $row['species_name'] . "</option>";
        }
        ?>
    </select><br>
    
    <label for="shelter_id">Shelter:</label>
    <select name="shelter_id" required>
        <?php
        $sql = "SELECT * FROM shelters";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['shelter_id'] . "'>" . $row['name'] . "</option>";
        }
        ?>
    </select><br>
    
    <label for="status">Status:</label>
    <input type="text" name="status" required><br>
    
    <label for="arrival_date">Arrival Date:</label>
    <input type="date" name="arrival_date" required><br>
    
    <input type="submit" name="submit" value="Add Pet">
</form>
<br><br>
    <form action="dashboard.php" method="get">
    <button type="submit">Back to Admin Dashboard</button>
</form>