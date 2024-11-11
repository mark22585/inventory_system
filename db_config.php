<?php
// Database configuration
$servername = "localhost";
$username = "root";  // Default for XAMPP
$password = "";      // Default for XAMPP (empty)
$dbname = "inventory_system";  // Replace with your DB name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
