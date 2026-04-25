<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("includes/db_connect.php");
//In our system, login is role base 

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
                // Fetching staff information using user_id
                $staff_query = "SELECT * FROM staff WHERE user_id = '" . $user["user_id"] . "'";
                $staff_result = mysqli_query($conn, $staff_query);
                
                if (mysqli_num_rows($staff_result) > 0) {
                    $staff_row = mysqli_fetch_assoc($staff_result);
                    $_SESSION['staff_id'] = $staff_row['staff_id']; // Stores staff_id in session
                    $_SESSION['user_id'] = $user['user_id']; // Stores user_id in session
                    header("Location: staff/dashboard.php"); // Redirects to staff dashboard
                    exit();
                } else {
                    echo "No staff information found!";
                }
            } elseif ($user["role"] == "adopter") {
                 // Fetching adopter information using user_id
                $adopters_query = "SELECT * FROM adopters WHERE user_id = '" . $user["user_id"] . "'";
                $adopters_result = mysqli_query($conn, $adopters_query);
                
                if (mysqli_num_rows($adopters_result) > 0) {
                    $adopters_row = mysqli_fetch_assoc($adopters_result);
                    $_SESSION['adopter_id'] = $adopters_row['adopter_id']; // Stores adopter_id in session
                    $_SESSION['user_id'] = $user['user_id']; // Stores user_id in session
                header("Location: adopter/dashboard.php"); // Redirects to adopter dashboard
                exit();
            }
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "No account found with this email.";
    }
} }

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
    </div>
    <a href="index.php" class="back-link">← Back to Home</a> 

</body>
</html>