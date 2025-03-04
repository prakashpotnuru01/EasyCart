<?php
session_start(); 
include 'config.php';
include 'userNavbar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Please log in to view your orders!");
    exit;
}

$userId = intval($_SESSION['user_id']);

$query = "SELECT order_id, product_name, product_price, quantity, total_price, order_date 
          FROM orders 
          WHERE user_id = ? 
          ORDER BY order_date DESC, order_id ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>My Orders</h2>";

if ($result->num_rows > 0) {
    $currentOrderId = null; 
    while ($row = $result->fetch_assoc()) {
        if ($currentOrderId !== $row['order_id']) {
            if ($currentOrderId !== null) {
                echo "</table><br>";
            }

            $currentOrderId = $row['order_id'];
            echo "<h4>Order ID: " . htmlspecialchars($row['order_id'], ENT_QUOTES, 'UTF-8') . "</h4>";
            echo "<p><strong>Order Date:</strong> " . htmlspecialchars($row['order_date'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<table border='1' cellspacing='0' cellpadding='5'>";
            echo "<tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                  </tr>";
        }

        echo "<tr>
                <td>" . htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>₹" . htmlspecialchars($row['product_price'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>₹" . htmlspecialchars($row['total_price'], ENT_QUOTES, 'UTF-8') . "</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>You have not placed any orders yet.</p>";
}

echo "<a href='display.php'>Back to Products</a>";

$conn->close();
?>
