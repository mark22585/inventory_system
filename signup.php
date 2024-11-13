<?php
session_start();  // Start the session to handle session variables

// Include the database connection
include('db_config.php');

// Check if the form is submitted
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields!";
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement to insert new user
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', 'user')";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            // Success: Redirect to the login page
            $_SESSION['success'] = "Account created successfully!";
            header('Location: index.php');  // Redirect to login page
            exit;
        } else {
            // Error: Show MySQL error message
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS -->
</head>
<body>
    <div class="signup-container">
        <div class="signup-box">
            <h2>Sign Up</h2>

            <!-- Display error or success messages -->
            <?php
            if (isset($_SESSION['error'])) {
                echo "<p class='error'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo "<p class='success'>" . $_SESSION['success'] . "</p>";
                unset($_SESSION['success']);
            }
            ?>

            <!-- Sign-up form -->
            <form action="signup.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="signup">Sign Up</button>
            </form>

            <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
