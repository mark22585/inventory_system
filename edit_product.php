<?php
session_start();
include('db_config.php');

// Check if product id is provided in the URL
if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];

    // Fetch product details from the database
    $sql = "SELECT * FROM inventory WHERE id = $product_id AND user_id = {$_SESSION['user_id']}";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['error'] = "Product not found.";
        header('Location: user_dashboard.php');
        exit;
    }

    // Handle form submission to update product
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $quantity = (int)$_POST['quantity'];
        $price = (float)$_POST['price'];

        $update_sql = "UPDATE inventory SET name = '$name', description = '$description', quantity = $quantity, price = $price WHERE id = $product_id";
        if (mysqli_query($conn, $update_sql)) {
            $_SESSION['message'] = "Product updated successfully!";
            header('Location: user_dashboard.php');
            exit;
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
        }
    }
} else {
    $_SESSION['error'] = "Invalid product ID.";
    header('Location: user_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="edit-product-container">
        <h2>Edit Product</h2>

        <!-- Display success or error messages -->
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['message'])) {
            echo "<p class='success'>{$_SESSION['message']}</p>";
            unset($_SESSION['message']);
        }
        ?>

        <!-- Product Edit Form -->
        <form action="edit_product.php?id=<?php echo $product['id']; ?>" method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
            <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            <button type="submit">Update Product</button>
        </form>

        <!-- Back Button -->
        <a href="user_dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
