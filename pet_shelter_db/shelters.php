<?php
include("includes/db_connect.php");

$sql = "SELECT * FROM shelters";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelters</title>
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
    <section class="panel shelter-page">
        <h2>Shelter Information</h2>

        <div class="card-grid shelter-list">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='card shelter-card'>";
                    echo "<h3>" . $row['name'] . "</h3>";
                    echo "<p><strong>Location:</strong> " . $row['location'] . "</p>";
                    echo "<p><strong>Capacity:</strong> " . $row['capacity'] . "</p>";
                    echo "<p><strong>Contact:</strong> " . $row['contact_number'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No shelter information.</p>";
            }
            ?>
        </div>
    </section>
   
</main>

</body>
</html>