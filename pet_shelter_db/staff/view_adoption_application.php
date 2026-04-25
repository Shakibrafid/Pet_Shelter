<?php
include("../includes/db_connect.php"); 

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "staff") {
    header("Location: ../login.php");
    exit();
}
// This is for fetching pending adoption applications
$query = "SELECT a.*, p.name AS pet_name, u.name AS adopter_name
          FROM adoption_applications a
          JOIN pets p ON a.pet_id = p.pet_id
          JOIN adopters ad ON a.adopter_id = ad.adopter_id
          JOIN users u ON ad.user_id = u.user_id
          WHERE a.status = 'Pending'";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption Application</title>
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
<table class="data-table">
    <thead>
        <tr>
            <th>Adopter Name</th>
            <th>Pet Name</th>
            <th>Application Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['adopter_name']; ?></td>
                <td><?= $row['pet_name']; ?></td>
                <td><?= $row['application_date']; ?></td>
                <td>
                    <a href='approve_adoption.php?application_id=<?php echo $row['application_id']; ?>' class='btn'>Approve</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</div>

<a class="btn" href="dashboard.php">Back to Staff Dashboard</a>

</body>
</html>