<?php
session_start();  // Start the session to handle session variables

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: index.php');  // Redirect to login page if not logged in or not a user
    exit;
}

// Include database connection
include('db_config.php');

// Handle adding products to the inventory
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    $user_id = $_SESSION['user_id'];  // Get the logged-in user ID

    // Insert the product into inventory for the logged-in user
    $sql = "INSERT INTO inventory (user_id, name, description, quantity, price) 
            VALUES ('$user_id', '$name', '$description', $quantity, $price)";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Product added successfully!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
}

// Get all inventory items for the logged-in user
$sql = "SELECT * FROM inventory WHERE user_id = {$_SESSION['user_id']}";
$inventory = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS -->
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome to Your Dashboard, <?php echo $_SESSION['username']; ?>!</h1>
        <p>You are logged in as a user.</p>

        <!-- Display User Information -->
        <h3>User Information:</h3>
        <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>

        <!-- Display success/error message -->
        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='success'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>

        <!-- Form to add a product -->
        <h3>Add New Product</h3>
        <form action="user_dashboard.php" method="POST">
            <input type="text" name="name" placeholder="Product Name" required>
            <textarea name="description" placeholder="Product Description"></textarea>
            <input type="number" name="quantity" placeholder="Quantity" required>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <button type="submit">Add Product</button>
        </form>

        <!-- Display user-specific inventory -->
        <h3>Your Inventory</h3>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($inventory)): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>$<?php echo $row['price']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <br>

        <!-- Button to logout -->
        <form action="logout.php" method="POST">
            <button type="submit" name="logout">Logout</button>
        </form>
    </div>
</body>
</html>
