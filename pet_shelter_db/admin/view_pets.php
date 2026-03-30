<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include("../includes/db_connect.php");

$sql = "SELECT pets.pet_id, pets.name, pets.age, pets.gender, pets.color, pets.status, pets.arrival_date,
               breeds.breed_name, species.species_name, shelters.name AS shelter_name
        FROM pets
        LEFT JOIN breeds ON pets.breed_id = breeds.breed_id
        LEFT JOIN species ON pets.species_id = species.species_id
        LEFT JOIN shelters ON pets.shelter_id = shelters.shelter_id
        ORDER BY pets.name";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin: Available Pets</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
    <header class="navbar">
        <a class="logo" href="../index.php">Care<span>For</span>Paws</a>
        <nav class="menu-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="add_pet.php">Add Pet</a>
            <a href="view_staff.php">Staff</a>
            <a href="view_staff_assignments.php">Assignments</a>
            <a href="../logout.php" class="logout-btn">Logout</a>
        </nav>
    </header>

    <div class="pets-page">
        <h2>All Pets</h2>

        <div class="pet-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="pet-card">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><strong>Breed:</strong> <?php echo htmlspecialchars($row['breed_name']); ?></p>
                        <p><strong>Species:</strong> <?php echo htmlspecialchars($row['species_name']); ?></p>
                        <p><strong>Shelter:</strong> <?php echo htmlspecialchars($row['shelter_name']); ?></p>
                        <p><strong>Age:</strong> <?php echo intval($row['age']); ?> years</p>
                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($row['gender']); ?></p>
                        <p><strong>Color:</strong> <?php echo htmlspecialchars($row['color']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
                        <p><strong>Arrival:</strong> <?php echo htmlspecialchars($row['arrival_date']); ?></p>
                        <a href="delete_pet.php?pet_id=<?php echo intval($row['pet_id']); ?>" class="btn" style="background:#e94b3c;margin-top:10px;">Delete</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No pets available yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>