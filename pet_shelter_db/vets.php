<?php
include('includes/db_connect.php');

// Fetches all veterinarians from database
$sql = "SELECT * FROM veterinarians";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veterinarians</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="navbar">
    <a class="logo" href="index.php">Care<span>For</span>Paws</a>
    <nav class="menu-links">
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<main>
    <section class="panel vets-list">
        <h2>Our Veterinarians</h2>

        <div class="card-grid">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='card vet-card'>";
                    echo "<h3>" . $row['name'] . "</h3>";
                    echo "<p><strong>Phone:</strong> " . $row['phone'] . "</p>";
                    echo "<p><strong>Clinic:</strong> " . $row['clinic_name'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No veterinarians available at the moment.</p>";
            }
            ?>
        </div>
    </section>
</main>

</body>
</html>