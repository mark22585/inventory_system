<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: index.php');
    exit;
}
include('db_config.php');

// Handle adding products to the inventory
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO inventory (user_id, name, description, quantity, price) 
            VALUES ('$user_id', '$name', '$description', $quantity, $price)";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Product added successfully!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
}

// Get all inventory items
$sql = "SELECT * FROM inventory WHERE user_id = {$_SESSION['user_id']}";
$inventory = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <p>Your email: <?php echo $_SESSION['email']; ?></p>

        <!-- Add new product -->
        <h3>Add New Product</h3>
        <form action="user_dashboard.php" method="POST">
            <input type="text" name="name" placeholder="Product Name" required>
            <textarea name="description" placeholder="Product Description" required></textarea>
            <input type="number" name="quantity" placeholder="Quantity" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <button type="submit">Add Product</button>
        </form>

        <!-- Display success or error messages -->
        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='success'>{$_SESSION['message']}</p>";
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        }
        ?>

        <!-- Display inventory -->
        <h3>Your Inventory</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php while ($product = mysqli_fetch_assoc($inventory)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($product['price']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
