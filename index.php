<?php
session_start();  // Start the session to handle session variables

// Include the database connection
include('db_config.php');

// Check if the form is submitted
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Basic validation
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields!";
    } else {
        // Query the database for the user
        $sql = "SELECT * FROM users WHERE email = '$email'";  // Ensure this query is correct
        $result = mysqli_query($conn, $sql);

        // Debugging: Check if the query ran successfully
        if (!$result) {
            die("Error running query: " . mysqli_error($conn));  // If query fails, show error
        }

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Debugging: Check the user data
            // var_dump($user);  // Uncomment this line to see the fetched data (optional)

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];  // 'admin' or 'user'

                // Debugging: Confirm successful login
                // echo "Login successful. Redirecting...";  // Remove this in production

                // Redirect based on role
                if ($user['role'] == 'admin') {
                    header('Location: admin_dashboard.php');
                    exit;  // Stop the script to avoid further output
                } else {
                    header('Location: user_dashboard.php');
                    exit;  // Stop the script to avoid further output
                }
            } else {
                $_SESSION['error'] = "Incorrect password!";
            }
        } else {
            $_SESSION['error'] = "No user found with that email!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS -->
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <!-- Display error message if any -->
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);  // Unset the error after displaying it
        }
        ?>

        <!-- Login form -->
        <form action="index.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>
</body>
</html>
