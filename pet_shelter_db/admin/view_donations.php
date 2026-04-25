<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

include("../includes/db_connect.php");

$sql = "SELECT donations.donation_id, donations.donor_name, donations.amount, donations.donation_date, shelters.name AS shelter_name
        FROM donations
        LEFT JOIN shelters ON donations.shelter_id = shelters.shelter_id
        ORDER BY donations.donation_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Donations</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .donation-box {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        .donation-box h2 {
            text-align: center;
            color: #ff7a2f;
            margin-bottom: 20px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #ff7a2f;
            text-decoration: none;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #ff7a2f;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

    <div class="donation-box">

        <h2>Donation Records</h2>

        <?php if (mysqli_num_rows($result) > 0) { ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Donor Name</th>
                    <th>Amount</th>
                    <th>Donation Date</th>
                    <th>Shelter</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row["donation_id"]; ?></td>
                        <td><?php echo $row["donor_name"]; ?></td>
                        <td><?php echo $row["amount"]; ?></td>
                        <td><?php echo $row["donation_date"]; ?></td>
                        <td><?php echo $row["shelter_name"]; ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>No donations found.</p>
        <?php } ?>

                <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    </div>

</body>
</html>