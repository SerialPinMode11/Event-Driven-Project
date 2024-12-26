<?php
// Database connection details
$servername = "localhost"; // Hostname
$username = "root";        // MySQL username
$password = "";            // MySQL password (blank)
$dbname = "localst";     // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Connection failed
    die("Connection failed: " . $conn->connect_error);
}

// Uncomment the line below for debugging purposes (optional)
 //echo "Successfully connected to the database!";
?>

