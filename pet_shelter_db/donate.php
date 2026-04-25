<?php

include("includes/db_connect.php");

// Fetching shelters from the database
$shelter_query = "SELECT * FROM shelters"; // Assuming your shelter table is called 'shelters'
$shelter_result = mysqli_query($conn, $shelter_query);
?>




<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Donation - Care For Paws</title>
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
        <section class="form-container">
            <h2>Make a Donation</h2>
            <p style="text-align:center; margin-bottom:20px; color: var(--muted);">
                Support our shelters and help animals get the care they deserve.
            </p>
            <form action="donation_process.php" method="POST">
                <label for="donor_name">Donor Name:</label>
                <input type="text" id="donor_name" name="donor_name" required>

                <label for="amount">Donation Amount:</label>
                <input type="number" id="amount" name="amount" min="1" step="0.01" required>

                <label for="shelter">Select Shelter:</label>
                <select id="shelter" name="shelter_id" required>
                    <option value="">Select a Shelter</option>
                    <?php
                    if (mysqli_num_rows($shelter_result) > 0) {
                        while ($shelter = mysqli_fetch_assoc($shelter_result)) {
                            echo "<option value='" . $shelter['shelter_id'] . "'>" . $shelter['name'] . " - " . $shelter['location'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No shelters available</option>";
                    }
                    ?>
                </select>

                <button type="submit" class="btn">Donate</button>
            </form>
        </section>
    </main>

</body>
</html>