<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include("../includes/db_connect.php");

$sql = "SELECT sa.assignment_id, u.name AS staff_name, p.name AS pet_name, sa.assigned_date
        FROM staff_assignments sa
        JOIN staff s ON sa.staff_id = s.staff_id
        JOIN users u ON s.user_id = u.user_id
        JOIN pets p ON sa.pet_id = p.pet_id
        ORDER BY sa.assigned_date DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin: Staff Assignments</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
    <header class="navbar">
        <a class="logo" href="../index.php">Care<span>For</span>Paws</a>
        <nav class="menu-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="view_pets.php">Pets</a>
            <a href="view_staff.php">Staff</a>
            <a href="../logout.php" class="logout-btn">Logout</a>
        </nav>
    </header>

    <div class="pets-page">
        <h2>Staff Assignments</h2>

        <div class="card-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="card">
                        <h3>Staff: <?php echo htmlspecialchars($row['staff_name']); ?></h3>
                        <p><strong>Pet:</strong> <?php echo htmlspecialchars($row['pet_name']); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($row['assigned_date']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No staff assignments available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>