<?php
include("../includes/db_connect.php"); 

session_start(); 
$user_id = $_SESSION['user_id']; 

// Checks if the user is already an adopter
$check_adopter = "SELECT adopter_id FROM adopters WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $check_adopter);

if (mysqli_num_rows($result) > 0) {
    // If adopter already exists, fetching adopter_id
    $adopter = mysqli_fetch_assoc($result);
    $adopter_id = $adopter['adopter_id'];
} else {
    // If adopter doesn't exist, inserts them into the adopters table
    $name = $_POST['name']; 
    $address = $_POST['address'];  
    $occupation = $_POST['occupation'];
    $house_type = $_POST['house_type'];  
    
    $insert_adopter = "INSERT INTO adopters (user_id, address, occupation, house_type) 
                       VALUES ('$user_id', '$address', '$occupation', '$house_type')";
    
    if (mysqli_query($conn, $insert_adopter)) {
        // After inserting, gets the adopter_id
        $adopter_id = mysqli_insert_id($conn);
    } else {
        echo "Error: " . mysqli_error($conn);
        exit;
    }
}

// Inserts the adoption application into the adoption_applications table
$pet_id = $_POST['pet_id'];
$application_date = date("Y-m-d"); // today's date
$status = "Pending"; // Application status

$insert_application = "INSERT INTO adoption_applications (adopter_id, pet_id, application_date, status) 
                       VALUES ('$adopter_id', '$pet_id', '$application_date', '$status')";

if (mysqli_query($conn, $insert_application)) {
    echo "Your adoption application has been successfully submitted!";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>