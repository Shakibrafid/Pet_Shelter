<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("includes/db_connect.php");

$message = "";
$message_color = "green";

//Volunteers or who wants to join as staff can register from here. The data will store in users, role=staff.
//  In database  Admin will add them as staff or volunteer. 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $occupation = trim($_POST["occupation"]);

    $role = "staff";

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
        // Inserts into users table
        $user_sql = "INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)";
        $user_stmt = mysqli_prepare($conn, $user_sql);
        mysqli_stmt_bind_param($user_stmt, "sssss", $name, $email, $phone, $password, $role);
        if (mysqli_stmt_execute($user_stmt)) {
            $message = "Registration successful!";
            $message_color = "green";
        
        } else {
            $message = "User insert failed: " . mysqli_error($conn);
            $message_color = "red";
        }
    }}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Registration</title>
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

    <div class="form-container">
        <h2>Volunteer Registration</h2>

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

            <button type="submit">Register</button>
        </form>

        <p style="text-align:center; margin-top: 15px;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>

</body>
</html>