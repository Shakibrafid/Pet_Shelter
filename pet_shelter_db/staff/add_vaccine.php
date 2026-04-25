<?php
session_start();
include("../includes/db_connect.php");


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php"); 
    exit();
}

// Fetching available pets 
$pet_query = "SELECT * FROM pets WHERE status = 'Available'"; 
$pet_result = mysqli_query($conn, $pet_query);

// Process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST['pet_id'];  // Gets the pet ID from the form
    $vaccine_name = $_POST['vaccine_name'];  // Gets vaccine name from form
    $vaccination_date = $_POST['vaccination_date'];  // Gets vaccination date from form
    $next_due_date = date('Y-m-d', strtotime('+1 year', strtotime($vaccination_date)));  // Next due date is 1 year from vaccination

    // Checks if the pet already has this vaccine recorded
    $vaccination_check_query = "SELECT * FROM vaccinations WHERE pet_id = '$pet_id' AND vaccine_name = '$vaccine_name'";
    $vaccination_check_result = mysqli_query($conn, $vaccination_check_query);

    if (mysqli_num_rows($vaccination_check_result) > 0) {
        echo "This vaccine has already been administered to this pet.";
    } else {
        // If no record exists, inserts the new vaccination record 
        $vaccination_query = "INSERT INTO vaccinations (pet_id, vaccine_name, vaccination_date, next_due_date) 
                              VALUES ('$pet_id', '$vaccine_name', '$vaccination_date', '$next_due_date')";

        if (mysqli_query($conn, $vaccination_query)) {
            echo "Vaccination record added successfully!";
        } else {
            echo "Error adding vaccination record: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vaccination Record</title>
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
<h2>Add Vaccination Record</h2>

<?php if (mysqli_num_rows($pet_result) > 0) { ?>
    <!-- Form for adding vaccination details-->
    <form method="POST" action="add_vaccine.php">
        <label for="pet_id">Select Pet:</label>
        <select name="pet_id" required>
            <option value="">Select a Pet</option>
            <?php while ($pet = mysqli_fetch_assoc($pet_result)) { ?>
                <option value="<?php echo $pet['pet_id']; ?>"><?php echo $pet['name']; ?></option>
            <?php } ?>
        </select>

        <label for="vaccine_name">Select Vaccine:</label>
        <select name="vaccine_name" required>
            <option value="Rabies">Rabies</option>
            <option value="Distemper">Distemper</option>
            <option value="Parvovirus">Parvovirus</option>
            <option value="Feline Leukemia">Feline Leukemia</option>
            <option value="Bird Flu">Bird Flu</option>
            <option value="FVRCP">FVRCP</option>
            <option value="DHPP/DAP">DHPP/DAP</option>
            <option value="Myxomatosis">Myxomatosis</option>
        </select>

        <label for="vaccination_date">Vaccination Date:</label>
        <input type="date" name="vaccination_date" required>

        <button type="submit" class="btn">Add Vaccination</button>
    </form>
<?php } else { ?>
    <p>No available pets to add vaccination record for at the moment.</p>
<?php } ?>

</div>

<a class="btn" href="dashboard.php">Back to Staff Dashboard</a>

</body>
</html>