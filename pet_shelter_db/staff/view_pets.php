<?php
session_start(); 


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
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

<div class="card-grid">
<?php
// Fetches all pets with details
$sql = "SELECT pets.pet_id, pets.name, pets.age, pets.gender, pets.color, 
               breeds.breed_name, species.species_name, shelters.name AS shelter_name
        FROM pets
        LEFT JOIN breeds ON pets.breed_id = breeds.breed_id
        LEFT JOIN species ON pets.species_id = species.species_id
        LEFT JOIN shelters ON pets.shelter_id = shelters.shelter_id";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Displays pet details
        echo "<div class='pet-card'>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "<p>Breed: " . $row['breed_name'] . "</p>";
        echo "<p>Species: " . $row['species_name'] . "</p>";
        echo "<p>Shelter: " . $row['shelter_name'] . "</p>";
        echo "<p>Age: " . $row['age'] . " years</p>";
        echo "<p>Gender: " . $row['gender'] . "</p>";
        echo "<p>Color: " . $row['color'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No pets available.</p>";
}
?>
</div>

<a class="btn" href="dashboard.php">Back to Staff Dashboard</a>

</body>
</html> 