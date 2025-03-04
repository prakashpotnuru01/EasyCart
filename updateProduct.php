<?php
include 'config.php';
include 'adminNavbar.php';

$product = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE product_Id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        die("Product not found!");
    }

    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productDescription = $_POST['product_description'];
    $productQuantity = $_POST['product_quantity'];
    $productPrice = $_POST['product_price'];

    $productImageLink = $product['product_imagelink'] ?? null; 
    if (!empty($_FILES['product_imagelink']['name'])) {
        if ($_FILES['product_imagelink']['error'] !== UPLOAD_ERR_OK) {
            die("Error uploading file: " . $_FILES['product_imagelink']['error']);
        }

        $uploadsDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        $productImageLink = basename($_FILES['product_imagelink']['name']);
        $target = $uploadsDir . $productImageLink;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['product_imagelink']['type'], $allowedTypes)) {
            die("Invalid file type. Only JPEG, PNG, and GIF are allowed.");
        }

        if (!move_uploaded_file($_FILES['product_imagelink']['tmp_name'], $target)) {
            die("Failed to upload image.");
        }
    }

    $imageLink = $productImageLink ?: "";

    $stmt = $conn->prepare("CALL updateProduct(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "isssii",
        $productId,
        $productName,
        $imageLink, 
        $productDescription,
        $productQuantity,
        $productPrice
    );

    if ($stmt->execute()) {
        echo "Product updated successfully.";
        header("Location: manageProducts.php");
        exit();
    } else {
        echo "Error updating product: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Product</title>
</head>
<body>
    <h1>Update Product</h1>

    <?php if (!empty($product)): ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8'); ?>">

            <label>Product Name:</label>
            <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

            <label>Current Image:</label><br>
            <?php if (!empty($product['product_imagelink'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($product['product_imagelink'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 150px; height: auto;"><br><br>
            <?php endif; ?>

            <label>Change Image:</label>
            <input type="file" name="product_imagelink"><br><br>

            <label>Product Description:</label>
            <input type="text" name="product_description" value="<?php echo htmlspecialchars($product['product_description'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

            <label>Quantity:</label>
            <input type="number" name="product_quantity" value="<?php echo htmlspecialchars($product['product_quantity'], ENT_QUOTES, 'UTF-8'); ?>" min="1" required><br><br>

            <label>Price:</label>
            <input type="number" name="product_price" value="<?php echo htmlspecialchars($product['product_price'], ENT_QUOTES, 'UTF-8'); ?>" min="0" required><br><br>

            <button type="submit">Update Product</button>
        </form>
    <?php else: ?>
        <p>Invalid Product ID or Product not found!</p>
    <?php endif; ?>
</body>
</html>
