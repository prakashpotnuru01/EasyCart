<?php
session_start();

include 'config.php';
include 'userNavbar.php';

if (!isset($_GET['order_id'])) {
    echo "<p>No order ID provided. Please go back and try again.</p>";
    exit;
}

$orderId = intval($_GET['order_id']);

$query = "SELECT o.product_name, o.product_price, o.quantity, 
                 (o.product_price * o.quantity) AS total_price, p.product_Id 
          FROM orders o 
          JOIN products p ON o.product_id = p.product_Id 
          WHERE o.order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();

    echo "<h1>Order Confirmed</h1>";
    echo "<p>Thank you for your order!</p>";
    echo "<h5>Order Details:</h5>";
    echo "<p><strong>Product Name:</strong> " . htmlspecialchars($order['product_name'], ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<p><strong>Price per Unit:</strong> ₹" . htmlspecialchars($order['product_price'], ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<p><strong>Quantity:</strong> " . htmlspecialchars($order['quantity'], ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<p><strong>Total Price:</strong> ₹" . htmlspecialchars($order['total_price'], ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<p>Your order has been placed successfully!</p>";
    echo "<a href='display.php'>Back to Products</a><br><br>";
    echo "<a href='myOrders.php'>My Orders</a>";
} else {
    echo "<p>Order not found. Please try again.</p>";
}

$conn->close();
?>
