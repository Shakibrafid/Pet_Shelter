<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include("../includes/db_connect.php");

$feedback = "";
$feedback_color = "green";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $gender = trim($_POST['gender']);
    $color = trim($_POST['color']);
    $status = trim($_POST['status']);
    $arrival_date = $_POST['arrival_date'];
    $breed_id = intval($_POST['breed_id']);
    $species_id = intval($_POST['species_id']);
    $shelter_id = intval($_POST['shelter_id']);

    $stmt = mysqli_prepare($conn, "INSERT INTO pets (name, age, gender, color, status, arrival_date, breed_id, species_id, shelter_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sissssiii", $name, $age, $gender, $color, $status, $arrival_date, $breed_id, $species_id, $shelter_id);

    if (mysqli_stmt_execute($stmt)) {
        $feedback = "New pet added successfully.";
        $feedback_color = "green";
    } else {
        $feedback = "Failed to add new pet: " . mysqli_error($conn);
        $feedback_color = "red";
    }

    mysqli_stmt_close($stmt);
}

// Fetch dropdown data once
$breeds = mysqli_query($conn, "SELECT breed_id, breed_name FROM breeds ORDER BY breed_name");
$species = mysqli_query($conn, "SELECT species_id, species_name FROM species ORDER BY species_name");
$shelters = mysqli_query($conn, "SELECT shelter_id, name FROM shelters ORDER BY name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Pet</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="navbar">
        <a class="logo" href="../index.php">Care<span>For</span>Paws</a>
        <nav class="menu-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="view_pets.php">View Pets</a>
            <a href="../logout.php" class="logout-btn">Logout</a>
        </nav>
    </header>

    <div class="form-container">
        <h2>Add New Pet</h2>

        <?php if (!empty($feedback)): ?>
            <p style="text-align:center; color: <?php echo $feedback_color; ?>; font-weight:bold;">
                <?php echo $feedback; ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Pet Name</label>
            <input type="text" name="name" required>

            <label>Age</label>
            <input type="number" min="0" name="age" required>

            <label>Gender</label>
            <select name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <label>Color</label>
            <input type="text" name="color" required>

            <label>Status</label>
            <select name="status" required>
                <option value="Available">Available</option>
                <option value="Adopted">Adopted</option>
                <option value="Pending">Pending</option>
            </select>

            <label>Arrival Date</label>
            <input type="date" name="arrival_date" required>

            <label>Breed</label>
            <select name="breed_id" required>
                <?php while ($row = mysqli_fetch_assoc($breeds)): ?>
                    <option value="<?php echo $row['breed_id']; ?>"><?php echo htmlspecialchars($row['breed_name']); ?></option>
                <?php endwhile; ?>
            </select>

            <label>Species</label>
            <select name="species_id" required>
                <?php while ($row = mysqli_fetch_assoc($species)): ?>
                    <option value="<?php echo $row['species_id']; ?>"><?php echo htmlspecialchars($row['species_name']); ?></option>
                <?php endwhile; ?>
            </select>

            <label>Shelter</label>
            <select name="shelter_id" required>
                <?php while ($row = mysqli_fetch_assoc($shelters)): ?>
                    <option value="<?php echo $row['shelter_id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Add Pet</button>
        </form>
    </div>
</body>
</html>