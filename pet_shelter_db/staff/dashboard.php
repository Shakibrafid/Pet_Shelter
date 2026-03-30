<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "staff") {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="form-container">
        <h2>Staff Dashboard</h2>
        <p>Welcome, <?php echo $_SESSION["name"]; ?>!</p>
        <p>Your role is: <?php echo $_SESSION["role"]; ?></p>
        <a href="../logout.php">Logout</a>
    </div>

</body>
</html>