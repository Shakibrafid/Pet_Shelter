<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");  
    exit();
}
include("../includes/db_connect.php"); 

// Gets the item_id from the URL
$item_id = isset($_GET['item_id']) ? $_GET['item_id'] : null;

//If no item_id provided, redirects to inventory view page
if ($item_id === null) {
    header("Location: view_inventory.php");
    exit;
}

//Checks if the item exists in the database
$sql_check = "SELECT * FROM inventory WHERE item_id = $item_id";
$result = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($result) > 0) {
    // Item exists, proceed to delete it
    $sql = "DELETE FROM inventory WHERE item_id = $item_id";
    
    if (mysqli_query($conn, $sql)) {
        //If Deletion is successful, redirects to view inventory
        header("Location: view_inventory.php");
        exit;
    } else {
        // Else displays the error message
        echo "Error deleting item: " . mysqli_error($conn);
    }
} else {
    // If Item is not found, redirects to inventory view page
    header("Location: view_inventory.php");
    exit;
}
?>