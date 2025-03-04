 
    }

    $productImageLink = basename($_FILES['product_imagelink']['name']);
    $target = $uploadsDir . $productImageLink;


    if (!move_uploaded_file($_FILES['product_imagelink']['tmp_name'], $target)) {
        die("Failed to upload image.");
    }

    $productDescription = $_POST['product_description'];
    $productQuantity = $_POST['product_quantity'];
    $productPrice = $_POST['product_price'];

    $query = "CALL addProduct(?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssii", $productName, $productImageLink, $productDescription, $productQuantity, $productPrice);

    if ($stmt->execute()) {
        echo "Product added successfully.";
        header("location: manageProducts.php");
    } else {
        echo "Error adding product: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
</head>
<body>
    <h2>Add Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Product Name:</label>
        <input type="text" name="product_name" required><br><br>

        <label>Product Image:</label>
        <input type="file" name="product_imagelink" required><br><br>

        <label>Product Description:</label>
        <input type="text" name="product_description" required><br><br>

        <label>Quantity:</label>
        <input type="number" name="product_quantity" min="1" required><br><br>

        <label>Price:</label>
        <input type="number" name="product_price" min="0" required><br><br>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>
