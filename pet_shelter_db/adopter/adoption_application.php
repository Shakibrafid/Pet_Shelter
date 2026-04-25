<?php
session_start();
include("../includes/db_connect.php");  

// Checks if adopter is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'adopter') {
    header("Location: ../login.php");  
    exit();
}

$adopter_id = $_SESSION['adopter_id'];  // Gets adopter's ID from session

// Fetching available pets
$pet_query = "SELECT * FROM pets WHERE status = 'Available'";
$pet_result = mysqli_query($conn, $pet_query);

// Process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST['pet_id'];  // Gets selected pet ID from the form

    // Inserts the adoption application into adoption_applications table
    $application_query = "INSERT INTO adoption_applications (adopter_id, pet_id, application_date, status)
                          VALUES ('$adopter_id', '$pet_id', NOW(), 'Pending')";

    if (mysqli_query($conn, $application_query)) {
        echo "Application submitted successfully! Waiting for approval.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Adoption</title>
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
    <h2>Apply for Adoption</h2>
    <form method="POST" action="adoption_application.php">
        <label for="pet_id">Select Pet:</label>
        <select name="pet_id" required>
            <option value="">Select a Pet</option>
            <?php while ($pet = mysqli_fetch_assoc($pet_result)) { ?>
                <option value="<?php echo $pet['pet_id']; ?>"><?php echo $pet['name']; ?></option>
            <?php } ?>
        </select>
        <button type="submit" class="btn">Apply</button>
    </form>
</div>

<a class="btn" href="dashboard.php">Back to Adopter Dashboard</a>

</body>
</html>