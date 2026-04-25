<?php
session_start();
include("../includes/db_connect.php");  


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'adopter') {
    header("Location: ../login.php");  
    exit();
}
//For payment in our system, only approved application can pay for their chosen pet.

$adopter_id = $_SESSION['adopter_id'];  // Get adopter's ID from session

// Fetching the latest adoption application for the logged-in adopter
$adoption_query = "SELECT * FROM adoption_applications WHERE adopter_id = '$adopter_id' AND status = 'Approved' ORDER BY application_date DESC LIMIT 1";
$adoption_result = mysqli_query($conn, $adoption_query);

// Checks if adoption record exists
if ($adoption_result && mysqli_num_rows($adoption_result) > 0) {
    $adoption = mysqli_fetch_assoc($adoption_result);
    $adoption_id = $adoption['application_id'];  // Gets adoption_id from adoption_applications
    $pet_id = $adoption['pet_id'];  // Gets pet_id from adoption_applications

    // Fetching the pet's details including breed and shelter info
    $pet_query = "
    SELECT 
        pets.name AS pet_name, 
        breeds.breed_name, 
        shelters.name AS shelter_name
    FROM pets
    LEFT JOIN breeds ON pets.breed_id = breeds.breed_id
    LEFT JOIN shelters ON pets.shelter_id = shelters.shelter_id
    WHERE pets.pet_id = '$pet_id' AND pets.status = 'Available'";

    $pet_result = mysqli_query($conn, $pet_query);
    $pet = mysqli_fetch_assoc($pet_result);

    // If the pet is already adopted, shows an error message
    if (!$pet) {
        echo "This pet has already been adopted or is not available.";
        exit();
    }

    // Process the payment when the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $amount = $_POST['amount'];  // Payment amount
        $payment_method = $_POST['payment_method']; //payment method
        $payment_date = date('Y-m-d');  // Current date as payment date

        // Checks if the adoption record exists in the adoptions table
        $adoption_check_query = "SELECT * FROM adoption WHERE adoption_id = '$adoption_id' AND adopter_id = '$adopter_id'";
        $adoption_check_result = mysqli_query($conn, $adoption_check_query);

        if (mysqli_num_rows($adoption_check_result) > 0) {
            // Inserts payment details into the payments table
            $payment_query = "INSERT INTO payment (adopter_id, amount, payment_date, payment_method) 
                              VALUES ('$adopter_id', '$amount', '$payment_date', '$payment_method')";
            
            if (mysqli_query($conn, $payment_query)) {
                // After successful payment, updates pet status to "Adopted"
                $update_pet_query = "UPDATE pets SET status = 'Adopted' WHERE pet_id = '$pet_id'"; 

                if (mysqli_query($conn, $update_pet_query)) {
                    echo "Thank you. Your adoption process is completed!";
                } else {
                    echo "Error updating pet status: " . mysqli_error($conn);
                }
            } else {
                echo "Error processing payment: " . mysqli_error($conn);
            }
        } else {
            echo "Adoption record does not exist or is not approved yet.";
        }
    }
} else {
    // If no adoption record found, this message shows
    echo "No approved adoption found for this adopter.";
    exit();  // Stops execution if no adoption found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment for Adoption</title>
</head>
<body>

<div class="payment-form-container">
    <h2>Complete Your Payment</h2>

    <?php if (isset($pet) && $pet) { ?>
        <!-- Displaying Pet Information in the Payment Form -->
        <p><strong>Pet Name:</strong> <?php echo $pet['pet_name']; ?></p>
        <p><strong>Pet Breed:</strong> <?php echo $pet['breed_name']; ?></p>
        <p><strong>Pet Shelter:</strong> <?php echo $pet['shelter_name']; ?></p>

        <!-- Form for Payment -->
        <form method="POST" action="payment.php">

            <label for="amount">Payment Amount:</label>
            <input type="number" name="amount" required>

            <label for="payment_method">Payment Method:</label>
            <select name="payment_method" required>
                <option value="Credit Card">Credit Card</option>
                <option value="Cash">Cash</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="bKash">bKash</option>
            </select>

            <button type="submit">Submit Payment</button>
        </form>
    <?php } else { ?>
        <p>No approved adoption found. You cannot proceed with the payment.</p>
    <?php } ?>
</div>

</form>
    <br><br>
    <form action="dashboard.php" method="get">
    <button type="submit">Back to Admin Dashboard</button>
</form> 

</body>
</html>