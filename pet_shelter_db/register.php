<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("includes/db_connect.php");

$message = "";
$message_color = "green";
//For adopters registration

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $occupation = trim($_POST["occupation"]);
    $house_type = trim($_POST["house_type"]);

    $role = "adopter";

    // Checks if email already exists
    $check_sql = "SELECT * FROM users WHERE email = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $email);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {
        $message = "Email already exists. Try another email.";
        $message_color = "red";
    } else {
        // Inserts into users table as adopter
        $user_sql = "INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)";
        $user_stmt = mysqli_prepare($conn, $user_sql);
        mysqli_stmt_bind_param($user_stmt, "sssss", $name, $email, $phone, $password, $role);

        if (mysqli_stmt_execute($user_stmt)) {
            $user_id = mysqli_insert_id($conn);

            // Insert into adopters table
            $adopter_sql = "INSERT INTO adopters (user_id, address, occupation, house_type) VALUES (?, ?, ?, ?)";
            $adopter_stmt = mysqli_prepare($conn, $adopter_sql);
            mysqli_stmt_bind_param($adopter_stmt, "isss", $user_id, $address, $occupation, $house_type);

            if (mysqli_stmt_execute($adopter_stmt)) {
                $message = "Registration successful!";
                $message_color = "green";
            } else {
                $message = "Adopter data insert failed: " . mysqli_error($conn);
                $message_color = "red";
            }
        } else {
            $message = "User insert failed: " . mysqli_error($conn);
            $message_color = "red";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adopter Registration</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="form-container">
        <h2>Adopter Registration</h2>

        <?php if (!empty($message)) { ?>
            <p style="text-align:center; color: <?php echo $message_color; ?>; font-weight:bold;">
                <?php echo $message; ?>
            </p>
        <?php } ?>

        <form action="" method="POST">
            <label>Full Name</label>
            <input type="text" name="full_name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Phone</label>
            <input type="text" name="phone" required>

            <label>Address</label>
            <textarea name="address" rows="3" required></textarea>

            <label>Occupation</label>
            <input type="text" name="occupation" required>

            <label>House Type</label>
            <input type="text" name="house_type" placeholder="Example: Apartment / Family House" required>

            <button type="submit">Register</button>
        </form>
    </div>
    <a href="index.php" class="back-link">← Back to Home</a>
</body>
</html>