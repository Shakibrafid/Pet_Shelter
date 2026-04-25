<?php
include("includes/db_connect.php");

$pet_id = isset($_GET['pet_id']) ? (int)$_GET['pet_id'] : 0;

if ($pet_id <= 0) {
    echo "Invalid pet ID.";
    exit();
}

// Fetching pet's basic info
$pet_sql = "SELECT pets.*, breeds.breed_name, species.species_name, shelters.name AS shelter_name
            FROM pets
            LEFT JOIN breeds ON pets.breed_id = breeds.breed_id
            LEFT JOIN species ON pets.species_id = species.species_id
            LEFT JOIN shelters ON pets.shelter_id = shelters.shelter_id
            WHERE pets.pet_id = $pet_id";

$pet_result = mysqli_query($conn, $pet_sql);

if (mysqli_num_rows($pet_result) == 0) {
    echo "Pet not found.";
    exit();
}

$pet = mysqli_fetch_assoc($pet_result);

//Fetching vaccinations record
$vacc_sql = "SELECT * FROM vaccinations WHERE pet_id = $pet_id";
$vacc_result = mysqli_query($conn, $vacc_sql);

//Fetching medical records 
$med_sql = "SELECT * FROM medical_records WHERE pet_id = $pet_id";
$med_result = mysqli_query($conn, $med_sql);

//Fetching training records
$train_sql = "SELECT * FROM training_records WHERE pet_id = $pet_id";
$train_result = mysqli_query($conn, $train_sql);

// Fetch rescue reports 
$rescue_sql = "SELECT rescue_reports.*, staff.staff_id, users.name AS rescuer_name
               FROM rescue_reports
               LEFT JOIN staff ON rescue_reports.rescued_by = staff.staff_id
               LEFT JOIN users ON staff.user_id = users.user_id
               WHERE rescue_reports.pet_id = $pet_id";
$rescue_result = mysqli_query($conn, $rescue_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Details</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .details-container {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
        }

        .section-box {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        .section-box h2, .section-box h3 {
            color: #ff7a2f;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #ff7a2f;
            text-decoration: none;
            font-weight: bold;
        }

        .record-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .record-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

<header class="navbar">
    <a class="logo" href="index.php">Care<span>For</span>Paws</a>
    <nav class="menu-links">
        <a href="index.php">Home</a>
        <a href="pets.php">Our Pets</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<div class="panel details-container">
    <a href="pets.php" class="back-link">← Back to Pets</a>

    <div class="section-box">
        <h2><?php echo $pet['name']; ?></h2>
        <p><strong>Breed:</strong> <?php echo $pet['breed_name']; ?></p>
        <p><strong>Species:</strong> <?php echo $pet['species_name']; ?></p>
        <p><strong>Shelter:</strong> <?php echo $pet['shelter_name']; ?></p>
        <p><strong>Age:</strong> <?php echo $pet['age']; ?> years</p>
        <p><strong>Gender:</strong> <?php echo $pet['gender']; ?></p>
        <p><strong>Color:</strong> <?php echo $pet['color']; ?></p>
        <p><strong>Status:</strong> <?php echo $pet['status']; ?></p>
        <p><strong>Arrival Date:</strong> <?php echo $pet['arrival_date']; ?></p>
    </div>

    <div class="section-box">
        <h3>Vaccinations</h3>
        <?php
        if (mysqli_num_rows($vacc_result) > 0) {
            while ($row = mysqli_fetch_assoc($vacc_result)) {
                echo "<div class='record-item'>";
                echo "<p><strong>Vaccine:</strong> " . $row['vaccine_name'] . "</p>";
                echo "<p><strong>Vaccination Date:</strong> " . $row['vaccination_date'] . "</p>";
                echo "<p><strong>Next Due Date:</strong> " . $row['next_due_date'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No vaccination records found.</p>";
        }
        ?>
    </div>

    <div class="section-box">
        <h3>Medical Records</h3>
        <?php
        if (mysqli_num_rows($med_result) > 0) {
            while ($row = mysqli_fetch_assoc($med_result)) {
                echo "<div class='record-item'>";
                echo "<p><strong>Diagnosis:</strong> " . $row['diagnosis'] . "</p>";
                echo "<p><strong>Treatment:</strong> " . $row['treatment'] . "</p>";
                echo "<p><strong>Visit Date:</strong> " . $row['visit_date'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No medical records found.</p>";
        }
        ?>
    </div>

    <div class="section-box">
        <h3>Training Records</h3>
        <?php
        if (mysqli_num_rows($train_result) > 0) {
            while ($row = mysqli_fetch_assoc($train_result)) {
                echo "<div class='record-item'>";
                echo "<p><strong>Trainer:</strong> " . $row['trainer_name'] . "</p>";
                echo "<p><strong>Training Type:</strong> " . $row['training_type'] . "</p>";
                echo "<p><strong>Completion Date:</strong> " . $row['completion_date'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No training records found.</p>";
        }
        ?>
    </div>

    <div class="section-box">
        <h3>Rescue Reports</h3>
        <?php
        if (mysqli_num_rows($rescue_result) > 0) {
            while ($row = mysqli_fetch_assoc($rescue_result)) {
                echo "<div class='record-item'>";
                echo "<p><strong>Rescue Location:</strong> " . $row['rescue_location'] . "</p>";
                echo "<p><strong>Rescue Date:</strong> " . $row['rescue_date'] . "</p>";
                echo "<p><strong>Rescued By:</strong> " . ($row['rescuer_name'] ? $row['rescuer_name'] : 'Unknown') . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No rescue reports found.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>