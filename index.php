<?php
session_start();  // Start the session to handle session variables
include('db_config.php');  // Include the database connection

// Check if a role is specified in the URL (e.g., role=admin or role=user)
if (isset($_GET['role'])) {
    $_SESSION['role'] = $_GET['role'];  // Set the role as a session variable
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];  // User's password

    // Basic validation to ensure fields are not empty
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields!";
    } else {
        // Use prepared statement to prevent SQL injection
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];  // 'admin' or 'user'

                // Redirect based on role
                if ($_SESSION['role'] == 'admin') {
                    header('Location: admin_dashboard.php');
                    exit;
                } else {
                    header('Location: user_dashboard.php');
                    exit;
                }
            } else {
                $_SESSION['error'] = "Incorrect password!";
            }
        } else {
            $_SESSION['error'] = "No user found with that email!";
        }
        $stmt->close();
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
        <div class="login-box">
            <h2>Login</h2>

            <!-- Display role selection buttons -->
            <div class="role-select">
                <a href="index.php?role=admin" class="role-button">Login as Admin</a>
                <a href="index.php?role=user" class="role-button">Login as User</a>
            </div>

            <!-- Display error message if any -->
            <?php
            if (isset($_SESSION['error'])) {
                echo "<p class='error'>" . htmlspecialchars($_SESSION['error']) . "</p>";
                unset($_SESSION['error']);
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
    </div>
</body>
</html>
