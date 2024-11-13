<?php
// Database connection
include('db_config.php');

// Admin credentials
$username = 'admin';
$email = 'admin@example.com';
$password = 'adminpassword';  // The password you want to set for the admin

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// SQL query to insert the admin user into the users table
$sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', 'admin')";

// Execute the query
if (mysqli_query($conn, $sql)) {
    echo "Admin user created successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
