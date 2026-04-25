<?php
session_start();
include("../includes/db_connect.php"); 


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");  
    exit();
}

// Fetching for available pets only
$pet_query = "SELECT * FROM pets WHERE status = 'Available'"; 
$pet_result = mysqli_query($conn, $pet_query);

// Process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST['pet_id'];  // Gets the pet ID from the form
    $diagnosis = $_POST['diagnosis'];  // Gets the diagnosis from form
    $treatment = $_POST['treatment'];  // Gets the treatment from form
    $visit_date = $_POST['visit_date'];  // Gets the visit date from form

    // Checking if a medical record already exists for this pet on the given visit date
    $medical_check_query = "SELECT * FROM medical_records WHERE pet_id = '$pet_id' AND visit_date = '$visit_date'";
    $medical_check_result = mysqli_query($conn, $medical_check_query);

    if (mysqli_num_rows($medical_check_result) > 0) {
        echo "A medical record already exists for this pet on the selected visit date.";
    } else {
        // If no record exists, inserts the new medical record
        $medical_query = "INSERT INTO medical_records (pet_id, diagnosis, treatment, visit_date) 
                          VALUES ('$pet_id', '$diagnosis', '$treatment', '$visit_date')";

        if (mysqli_query($conn, $medical_query)) {
            echo "Medical record added successfully!";
        } else {
            echo "Error adding medical record: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medical Record</title>
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
<h2>Add Medical Record</h2>

<?php if (mysqli_num_rows($pet_result) > 0) { ?>
<!-- Form to add medical records for selected pet -->

    <form method="POST" action="add_medical_record.php">
        <label for="pet_id">Select Pet:</label>
        <select name="pet_id" required>
            <option value="">Select a Pet</option>
            <?php while ($pet = mysqli_fetch_assoc($pet_result)) { ?>
                <option value="<?php echo $pet['pet_id']; ?>"><?php echo $pet['name']; ?></option>
            <?php } ?>
        </select>

        <label for="diagnosis">Diagnosis:</label>
        <input type="text" name="diagnosis" required>

        <label for="treatment">Treatment:</label>
        <input type="text" name="treatment" required>

        <label for="visit_date">Visit Date:</label>
        <input type="date" name="visit_date" required>

        <button type="submit" class="btn">Add Medical Record</button>
    </form>
<?php } else { ?>
    <p>No available pets to add medical records for at the moment.</p>
<?php } ?>

</div>

<a class="btn" href="dashboard.php">Back to Staff Dashboard</a>

</body>
</html>