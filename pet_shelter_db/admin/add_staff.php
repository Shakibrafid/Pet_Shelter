<?php
session_start();
include("../includes/db_connect.php"); 

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");  
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetching user ID and staff position from the form
    $user_id = $_POST['user_id'];
    $position = $_POST['position'];
    $hire_date = date('Y-m-d');  // Sets hire date to current date

    // Checking if the user has the role 'staff' in the users table
    $check_user_query = "SELECT * FROM users WHERE user_id = '$user_id' AND role = 'staff'";
    $check_user_result = mysqli_query($conn, $check_user_query);

    // Checking if the user exists in the staff table
    $check_staff_query = "SELECT * FROM staff WHERE user_id = '$user_id'";
    $check_staff_result = mysqli_query($conn, $check_staff_query);

    // If the user already exists in both the users table with 'staff' role and the staff table
    if (mysqli_num_rows($check_user_result) > 0 && mysqli_num_rows($check_staff_result) > 0) {
        echo "This staff member is already added.";  // Showing message if user is already a staff
    } else {
        // If the user doesn't exist in the staff table, inserts into the staff table
        $insert_query = "INSERT INTO staff (user_id, position, hire_date) VALUES ('$user_id', '$position', '$hire_date')";

        if (mysqli_query($conn, $insert_query)) {
            // Update the role in the users table to 'staff'
            $update_role_query = "UPDATE users SET role = 'staff' WHERE user_id = '$user_id'";

            if (mysqli_query($conn, $update_role_query)) {
                echo "New staff member added successfully!";
            } else {
                echo "Error updating user role: " . mysqli_error($conn);
            }
        } else {
            echo "Error adding staff member: " . mysqli_error($conn);
        }
    }
}

// Fetching staffs from users table those with role 'staff'
$staff_query = "SELECT * FROM users WHERE role = 'staff'";
$staff_result = mysqli_query($conn, $staff_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Staff</title>
</head>
<body>
    <h2>Add New Staff Member</h2>
    <form method="POST" action="add_staff.php">
        <label for="user_id">Select Staff:</label>
        <select name="user_id" required>
            <option value="">Select a Staff</option>
            <?php
            while ($row = mysqli_fetch_assoc($staff_result)) {
                echo "<option value='" . $row['user_id'] . "'>" . $row['name'] . "</option>";
            }
            ?>
        </select>

        <label for="position">Staff Position:</label>
        <select name="position" required>
            <option value="Staff Manager">Staff Manager</option>
            <option value="Trainer">Trainer</option>
            <option value="General Member">General Member</option>
            <option value="Volunteer">Volunteer</option>
            <option value="Shelter Manager">Shelter Manager</option>
            <option value="Volunteer Manager">Volunteer Manager</option>
            <option value="Pet Care Specialist">Pet Care Specialist</option>
            <option value="Animal Groomer">Animal Groomer</option>
            <option value="Animal Care Attendant">Animal Care Attendant</option>
            <option value="Shelter Veterinarian">Shelter Veterinarian</option>
        </select>

        <button type="submit">Add Staff</button>
    </form>
    <br><br>
    <form action="dashboard.php" method="get">
    <button type="submit">Back to Admin Dashboard</button>
</form>
</body>
</html>