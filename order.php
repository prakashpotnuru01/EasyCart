<?php 
session_start(); 
include 'config.php';
include 'userNavbar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Please log in to place an order!");
    exit;
}

$userId = intval($_SESSION['user_id']);

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    $query = "SELECT product_name, product_price, product_quantity FROM products WHERE product_Id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        if ($quantity > $product['product_quantity']) {
            header("Location: display.php?message=Not enough stock available!");
            exit;
        }

        $totalPrice = $product['product_price'] * $quantity;

        if (isset($_POST['confirm_order']) && $_POST['confirm_order'] == '1') {
            $newQuantity = $product['product_quantity'] - $quantity;
            $updateQuery = "UPDATE products SET product_quantity = ? WHERE product_Id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ii", $newQuantity, $productId);
            $updateStmt->execute();

            $insertOrderQuery = "INSERT INTO orders (user_id, product_id, product_name, product_price, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?)";
            $insertOrderStmt = $conn->prepare($insertOrderQuery);
            $insertOrderStmt->bind_param("iisdid", $userId, $productId, $product['product_name'], $product['product_price'], $quantity, $totalPrice);
            $insertOrderStmt->execute();


            $orderId = $conn->insert_id; 

            header("Location: confirmation.php?order_id={$orderId}");
            exit;
        }

        echo "<h1>Review Your Order</h1>";
        echo "<p><strong>Product Name:</strong> " . htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>Price per Unit:</strong> ₹" . htmlspecialchars($product['product_price'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>Quantity:</strong> " . htmlspecialchars($quantity, ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>Total Price:</strong> ₹" . htmlspecialchars($totalPrice, ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<form action='order.php' method='POST'>
            <input type='hidden' name='confirm_order' value='1'>
            <input type='hidden' name='product_id' value='{$productId}'>
            <input type='hidden' name='quantity' value='{$quantity}'>
            <button type='submit'>Confirm Order</button>
        </form>";
    } else {
        header("Location: display.php?message=Product not found!");
        exit;
    }
} else {
    header("Location: display.php?message=Invalid product data!");
    exit;
}

$conn->close();
?>
