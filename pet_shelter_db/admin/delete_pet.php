<?php
session_start();  // Starts the session
// Here in this system, after admin deletes the pet, its get deleted and redirects back to view pets 

// Checks if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");  // Redirect to login if not admin
    exit();  // Stops further execution
}
// Includes database connection
include("../includes/db_connect.php");

// Checks if the pet_id is provided in the URL 
if (isset($_GET['pet_id'])) {
    $pet_id = $_GET['pet_id'];

    //  deletes the pet with the given pet_id
    $sql = "DELETE FROM pets WHERE pet_id = '$pet_id'";

    if (mysqli_query($conn, $sql)) {
        echo "Pet deleted successfully!";
        header("Location: view_pets.php");  // Redirects back to View Pets page
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
} else {
    echo "No pet selected to delete.";
}
?>