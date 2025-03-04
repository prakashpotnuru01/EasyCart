<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];

    $stmt = $conn->prepare("CALL deleteProduct(?)");
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        header("Location: manageProducts.php");
    } else {
        echo "Error deleting product: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>
