<?php
session_start();
include("../includes/db_connect.php"); 


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");  
    exit();
}
//In our system , only one pet can be under one staff, only one staff can take care of one pet
//one-to-one relationship between staff-pet 

// Fetch the list of available pets and staff from the database
$pet_query = "SELECT * FROM pets WHERE status = 'Available'"; 
$pet_result = mysqli_query($conn, $pet_query);

$staff_query = "SELECT * FROM staff"; 
$staff_result = mysqli_query($conn, $staff_query);

// Process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_id = $_POST['staff_id']; // Gets the staff ID from the form
    $pet_id = $_POST['pet_id'];     // Gets the pet ID from the form

    //Checking if the pet is already assigned to a staff
    $assignment_check_query = "SELECT * FROM staff_assignments WHERE pet_id = '$pet_id'";
    $assignment_check_result = mysqli_query($conn, $assignment_check_query);

    if (mysqli_num_rows($assignment_check_result) > 0) {
        // If the pet is already assigned, this message shows
        echo "This pet is already assigned to a staff member.";
        exit(); 
    }

    //Checking if the staff is already assigned to another pet
    $staff_check_query = "SELECT * FROM staff_assignments WHERE staff_id = '$staff_id'";
    $staff_check_result = mysqli_query($conn, $staff_check_query);

    if (mysqli_num_rows($staff_check_result) > 0) {
        // If the staff is already assigned,this message shows
        echo "This staff is already assigned to another pet.";
        exit(); // Stop further execution
    }

    // If pet and staff are not already assigned, then assign the staff to the pet
    $assignment_query = "INSERT INTO staff_assignments (staff_id, pet_id, assigned_date) 
                         VALUES ('$staff_id', '$pet_id', NOW())";

    if (mysqli_query($conn, $assignment_query)) {
        // success message now
        echo "Staff has been successfully assigned to the pet.";
    } else {
        echo "Error assigning staff: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Staff to Pet</title>
</head>
<body>

<h2>Assign Staff to Pet</h2>

<?php if (mysqli_num_rows($pet_result) > 0) { ?>
    <!-- Form to assign staff to pet -->
    <form method="POST" action="add_staff_assignment.php">
        <label for="pet_id">Select Pet:</label>
        <select name="pet_id" required>
            <option value="">Select a Pet</option>
            <?php while ($pet = mysqli_fetch_assoc($pet_result)) { ?>
                <option value="<?php echo $pet['pet_id']; ?>"><?php echo $pet['name']; ?></option>
            <?php } ?>
        </select>

        <label for="staff_id">Select Staff:</label>
        <select name="staff_id" required>
            <option value="">Select a Staff</option>
            <?php while ($staff = mysqli_fetch_assoc($staff_result)) { ?>
                <option value="<?php echo $staff['staff_id']; ?>"><?php echo $staff['position']; ?> - <?php echo $staff['staff_id']; ?></option>
            <?php } ?>
        </select>

        <button type="submit">Assign Staff</button>
    </form>
<?php } else { ?>
    <p>No available pets to assign staff to at the moment.</p>
<?php } ?>
 <br><br>
 <form action="dashboard.php" method="get">
    <button type="submit">Back to Admin Dashboard</button>
</form>

</body>
</html>