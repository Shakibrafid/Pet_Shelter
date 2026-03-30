<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("includes/db_connect.php");

$message = "";
$message_color = "red";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if ($user["password"] == $password) {
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] == "admin") {
                header("Location: admin/dashboard.php");
                exit();
            } elseif ($user["role"] == "staff") {
                header("Location: staff/dashboard.php");
                exit();
            } elseif ($user["role"] == "adopter") {
                header("Location: adopter/dashboard.php");
                exit();
            }
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "No account found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="navbar">
        <a class="logo" href="index.php">Care<span>For</span>Paws</a>
        <nav class="menu-links">
            <a href="index.php">Home</a>
            <a href="register.php">Register</a>
        </nav>
    </header>

    <div class="form-container">
        <h2>User Login</h2>

        <?php if (!empty($message)) { ?>
            <p style="text-align:center; color: <?php echo $message_color; ?>; font-weight:bold;">
                <?php echo $message; ?>
            </p>
        <?php } ?>

        <form action="" method="POST" autocomplete="off">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p style="text-align:center; margin-top: 15px;">
            New user? <a href="register.php">Create account</a>
        </p>
    </div>
</body>
</html>