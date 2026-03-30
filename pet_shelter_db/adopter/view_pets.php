<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "adopter") {
    header("Location: ../login.php");
    exit();
}

include("../includes/db_connect.php");

$sql = "SELECT 
            pets.pet_id,
            pets.name,
            pets.age,
            pets.gender,
            pets.color,
            pets.status,
            pets.arrival_date,
            breeds.breed_name,
            shelters.name AS shelter_name,
            species.species_name
        FROM pets
        LEFT JOIN breeds ON pets.breed_id = breeds.breed_id
        LEFT JOIN shelters ON pets.shelter_id = shelters.shelter_id
        LEFT JOIN species ON pets.species_id = species.species_id
        WHERE pets.status = 'Available'";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Pets</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="pets-page">
        <h2>Available Pets</h2>

        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

        <div class="pet-grid">
           <?php
        if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='pet-card'>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "<p><strong>Age:</strong> " . $row['age'] . " years</p>";
        echo "<p><strong>Gender:</strong> " . $row['gender'] . "</p>";
        echo "<p><strong>Color:</strong> " . $row['color'] . "</p>";
        echo "<p><strong>Breed:</strong> " . $row['breed_name'] . "</p>";
        echo "<p><strong>Species:</strong> " . $row['species_name'] . "</p>";
        echo "<p><strong>Shelter:</strong> " . $row['shelter_name'] . "</p>";
        echo "<p><strong>Status:</strong> " . $row['status'] . "</p>";
        echo "<p><strong>Arrival Date:</strong> " . $row['arrival_date'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No available pets found.</p>";
}
?>
        </div>
    </div>

</body>
</html>