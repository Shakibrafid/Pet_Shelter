<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
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
    <section class="panel">
        <h2>Inventory</h2>

        <div class="card-grid">
            <?php
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='card'>";
                    echo "<h3>" . $row['item_name'] . "</h3>";
                    echo "<p><strong>Quantity:</strong> " . $row['quantity'] . "</p>";
                    echo "<p><strong>Shelter:</strong> " . $row['shelter_id'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No items in inventory.</p>";
            }
            ?>
        </div>
    </section>
</main>

</body>
</html>