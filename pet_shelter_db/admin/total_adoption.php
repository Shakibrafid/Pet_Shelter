<?php
session_start();
include("../includes/db_connect.php");  


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

//fetching total adoptions and details of adopter, adoption date, and pet name
$query = "
    SELECT 
        adoption.adoption_id, 
        adoption_applications.adopter_id, 
        adoption_applications.pet_id, 
        adoption.adoption_date, 
        pets.name AS pet_name, 
        users.name AS adopter_name
    FROM adoption
    JOIN adoption_applications ON adoption.application_id = adoption_applications.application_id
    JOIN pets ON adoption_applications.pet_id = pets.pet_id
    JOIN users ON adoption_applications.adopter_id = users.user_id
";

$result = mysqli_query($conn, $query);

if ($result) {
    // Count the total number of adoptions
    $count_query = "SELECT COUNT(*) AS total_adoptions FROM adoption";
    $count_result = mysqli_query($conn, $count_query);
    $total_adoptions = mysqli_fetch_assoc($count_result)['total_adoptions'];
} else {
    
    echo "Error: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption Applications</title>
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

<div class="panel">
<h2>Adoption Applications</h2>

<!-- Displays the total number of adoption applications -->
<p>Total Adoption Applications: <?php echo $total_adoptions; ?></p>

<!-- Displays the details of each adoption -->
<table class="data-table">
    <thead>
        <tr>
            <th>Adoption ID</th>
            <th>Adopter Name</th>
            <th>Pet Name</th>
            <th>Adoption Date</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['adoption_id']; ?></td>
                <td><?php echo $row['adopter_name']; ?></td>
                <td><?php echo $row['pet_name']; ?></td>
                <td><?php echo $row['adoption_date']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
</div>

<a class="btn" href="dashboard.php">Back to Admin Dashboard</a>

</body>
</html>