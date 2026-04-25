<?php
session_start();
include("../includes/db_connect.php");  

// Check if staff is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");  
    exit();
}

// Fetching pending adoption applications with pet name 
$adoption_query = "
    SELECT 
        aa.application_id, 
        aa.adopter_id, 
        aa.pet_id, 
        p.name AS pet_name
    FROM adoption_applications aa
    JOIN pets p ON aa.pet_id = p.pet_id
    WHERE aa.status = 'Pending'
";
$adoption_result = mysqli_query($conn, $adoption_query);

// Process the approval when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $application_id = $_POST['application_id'];  // Gets application ID from the form

    // Fetching the adopter_id and pet_id based on the selected application_id
    $adoption_details_query = "SELECT * FROM adoption_applications WHERE application_id = '$application_id'";
    $adoption_details_result = mysqli_query($conn, $adoption_details_query);

    if (mysqli_num_rows($adoption_details_result) > 0) {
        $adoption_details = mysqli_fetch_assoc($adoption_details_result);
        $adopter_id = $adoption_details['adopter_id'];  // Gets adopter_id
        $pet_id = $adoption_details['pet_id'];  // Gets pet_id

        // Updates adoption status to "Approved" in the adoption_applications table
        $update_status_query = "UPDATE adoption_applications SET status = 'Approved' WHERE application_id = '$application_id'";

        // Inserts into the adoption table
        $adoption_insert_query = "INSERT INTO adoption (application_id, adoption_date, approved_by, adopter_id) 
                                  VALUES ('$application_id', NOW(), '" . $_SESSION['staff_id'] . "', '$adopter_id')";

        if (mysqli_query($conn, $update_status_query) && mysqli_query($conn, $adoption_insert_query)) {
            echo "Adoption approved successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error: Application details not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Adoption</title>
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
    <h2>Approve Adoption</h2>
    <form method="POST" action="approve_adoption.php">
        <label for="application_id">Select Application:</label>
        <select name="application_id" required>
            <option value="">Select Application</option>
            <?php while ($application = mysqli_fetch_assoc($adoption_result)) { ?>
                <option value="<?php echo $application['application_id']; ?>">
                    Application ID: <?php echo $application['application_id']; ?> | 
                    Adopter ID: <?php echo $application['adopter_id']; ?> | 
                    Pet Name: <?php echo $application['pet_name']; ?> 
                </option>
            <?php } ?>
        </select>

        <button type="submit" class="btn">Approve Adoption</button>
    </form>
    </div>
    <a class="btn" href="dashboard.php">Back to Admin Dashboard</a>
</body>
</html>