<?php
include("includes/db_connect.php");

$sql = "SELECT pets.pet_id, pets.name, pets.age, pets.gender, pets.color, pets.status,
               breeds.breed_name, species.species_name, shelters.name AS shelter_name
        FROM pets
        LEFT JOIN breeds ON pets.breed_id = breeds.breed_id
        LEFT JOIN species ON pets.species_id = species.species_id
        LEFT JOIN shelters ON pets.shelter_id = shelters.shelter_id";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Pets</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="navbar">
    <a class="logo" href="index.php">Care<span>For</span>Paws</a>
    <nav class="menu-links">
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<main>
    <section class="pets-page">
        <h2>Our Pets</h2>

        <div class="pet-grid">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='pet-card'>";
                    echo "<h3>" . $row['name'] . "</h3>";
                    echo "<p><strong>Breed:</strong> " . $row['breed_name'] . "</p>";
                    echo "<p><strong>Species:</strong> " . $row['species_name'] . "</p>";
                    echo "<p><strong>Shelter:</strong> " . $row['shelter_name'] . "</p>";
                    echo "<p><strong>Age:</strong> " . $row['age'] . " years</p>";
                    echo "<p><strong>Gender:</strong> " . $row['gender'] . "</p>";
                    echo "<p><strong>Color:</strong> " . $row['color'] . "</p>";
                    echo "<p><strong>Status:</strong> " . $row['status'] . "</p>";
                    echo "<a href='pet_details.php?pet_id=" . $row['pet_id'] . "' class='btn'>View Details</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No pets available.</p>";
            }
            ?>
        </div>
    </section>

</main>

</body>
</html>