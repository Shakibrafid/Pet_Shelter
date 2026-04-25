<?php
session_start();
include("../includes/db_connect.php");  // Include database connection

// Check if staff is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");  // Redirect to login if not logged in
    exit();
}

// Fetch available pets (not adopted)
$pet_query = "SELECT * FROM pets";  // Only available pets 
$pet_result = mysqli_query($conn, $pet_query);

// Fetch all staff members (for rescued_by)
$staff_query = "SELECT * FROM staff";  // Fetch all staff members
$staff_result = mysqli_query($conn, $staff_query);

// Process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST['pet_id'];  // Get the pet ID from the form
    $rescue_location = $_POST['rescue_location'];  // Get the rescue location from the form
    $rescue_date = $_POST['rescue_date'];  // Get the rescue date from the form
    $rescued_by = $_POST['rescued_by'];  // Get the staff ID (rescued_by) from the form

    // Check if the pet already has a rescue report
    $rescue_check_query = "SELECT * FROM rescue_reports WHERE pet_id = '$pet_id'";
    $rescue_check_result = mysqli_query($conn, $rescue_check_query);

    if (mysqli_num_rows($rescue_check_result) > 0) {
        // If a report already exists, show error message
        echo "Rescue report already recorded for this pet.";
    } else {
        // If no report exists, insert the new rescue report
        $rescue_query = "INSERT INTO rescue_reports (pet_id, rescue_location, rescue_date, rescued_by) 
                         VALUES ('$pet_id', '$rescue_location', '$rescue_date', '$rescued_by')";

        if (mysqli_query($conn, $rescue_query)) {
            echo "Rescue report added successfully!";
        } else {
            echo "Error adding rescue report: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Rescue Report</title>
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
<h2>Add Rescue Report</h2>

<?php if (mysqli_num_rows($pet_result) > 0) { ?>
    <!-- Form to add rescue report details for the selected pet -->
    <form method="POST" action="add_rescue_report.php">
        <label for="pet_id">Select Pet:</label>
        <select name="pet_id" required>
            <option value="">Select a Pet</option>
            <?php while ($pet = mysqli_fetch_assoc($pet_result)) { ?>
                <option value="<?php echo $pet['pet_id']; ?>"><?php echo $pet['name']; ?></option>
            <?php } ?>
        </select>

        <label for="rescue_location">Rescue Location:</label>
        <input type="text" name="rescue_location" required placeholder="Enter Rescue Location">

        <label for="rescue_date">Rescue Date:</label>
        <input type="date" name="rescue_date" required>

        <label for="rescued_by">Staff Rescuing the Pet:</label>
        <select name="rescued_by" required>
            <option value="">Select Staff</option>
            <?php while ($staff = mysqli_fetch_assoc($staff_result)) { ?>
                <option value="<?php echo $staff['staff_id']; ?>"><?php echo $staff['position']; ?> - <?php echo $staff['staff_id']; ?></option>
            <?php } ?>
        </select>

        <button type="submit" class="btn">Add Rescue Report</button>
    </form>
<?php } else { ?>
    <p>No available pets to add rescue reports for at the moment.</p>
<?php } ?>

</div>

<a class="btn" href="dashboard.php">Back to Staff Dashboard</a>

</body>
</html>