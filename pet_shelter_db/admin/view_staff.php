<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include("../includes/db_connect.php");

$sql = "SELECT s.staff_id, s.position, u.name, u.email, u.phone, s.hire_date
        FROM staff s
        JOIN users u ON s.user_id = u.user_id
        ORDER BY u.name";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin: Staff</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
    <header class="navbar">
        <a class="logo" href="../index.php">Care<span>For</span>Paws</a>
        <nav class="menu-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="view_pets.php">Pets</a>
            <a href="view_staff_assignments.php">Assignments</a>
            <a href="../logout.php" class="logout-btn">Logout</a>
        </nav>
    </header>

    <div class="pets-page">
        <h2>Staff Members</h2>

        <div class="card-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?></p>
                        <p><strong>Position:</strong> <?php echo htmlspecialchars($row['position']); ?></p>
                        <p><strong>Hire Date:</strong> <?php echo htmlspecialchars($row['hire_date']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No staff available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>