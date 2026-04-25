<?php
session_start();
include("../includes/db_connect.php");  


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");  
    exit();
}

// Fetching available pets only
$pet_query = "SELECT * FROM pets WHERE status = 'Available'";  
$pet_result = mysqli_query($conn, $pet_query);

// Process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST['pet_id'];  // Gets the pet ID from the form
    $trainer_name = $_POST['trainer_name'];  // Gets the trainer's name from the form
    $training_type = $_POST['training_type'];  // Gets the training type from the form
    $completion_date = $_POST['completion_date'];  // Gets completion date from the form

    // Checking if the selected pet already has this training record
    $training_check_query = "SELECT * FROM training_records WHERE pet_id = '$pet_id' AND trainer_name = '$trainer_name' AND training_type = '$training_type'";
    $training_check_result = mysqli_query($conn, $training_check_query);

    if (mysqli_num_rows($training_check_result) > 0) {
        // If a record already exists, an error message shows
        echo "A training record already exists for this pet with the selected trainer.";
    } else {
        // If no record exists, inserts the new training record into the training_records table
        $training_query = "INSERT INTO training_records (pet_id, trainer_name, training_type, completion_date) 
                           VALUES ('$pet_id', '$trainer_name', '$training_type', '$completion_date')";

        if (mysqli_query($conn, $training_query)) {
            echo "Training record added successfully!";
        } else {
            echo "Error adding training record: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Training Record</title>
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
<h2>Add Training Record</h2>

<?php if (mysqli_num_rows($pet_result) > 0) { ?>
<!-- Form for adding training records  -->
    
    <form method="POST" action="add_training_record.php">
        <label for="pet_id">Select Pet:</label>
        <select name="pet_id" required>
            <option value="">Select a Pet</option>
            <?php while ($pet = mysqli_fetch_assoc($pet_result)) { ?>
                <option value="<?php echo $pet['pet_id']; ?>"><?php echo $pet['name']; ?></option>
            <?php } ?>
        </select>

        <label for="trainer_name">Trainer Name:</label>
        <input type="text" name="trainer_name" required placeholder="Enter Trainer Name">

        <label for="training_type">Training Type:</label>
        <input type="text" name="training_type" required placeholder="Enter Training Type">

        <label for="completion_date">Completion Date:</label>
        <input type="date" name="completion_date" required>

        <button type="submit" class="btn">Add Training Record</button>
    </form>
<?php } else { ?>
    <p>No available pets to add training records for at the moment.</p>
<?php } ?>

</div>

<a class="btn" href="dashboard.php">Back to Staff Dashboard</a>

</body>
</html>