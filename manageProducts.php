<?php
include 'config.php';
include 'adminNavbar.php';
session_start();

if($_SESSION['user_id'] != 1016){
    echo "Access Denied. Only Admin has to manage products";
    exit();
}

$result = $conn->query("SELECT * FROM products");

$products = [];
while ($row = $result->fetch_assoc()) {
    $row['product_imagelink'] = file_exists('uploads/' . $row['product_imagelink']) ? 
        'uploads/' . htmlspecialchars($row['product_imagelink'], ENT_QUOTES, 'UTF-8') : 
        'uploads/default.jpg';
    $products[] = $row;
}

$conn->close();
?>  


<!DOCTYPE html>                                                                                               
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        img {
            max-width: 100px;
            height: auto;
        }
        .action-buttons form {
            display: inline-block;
            margin: 0 5px;
        }
        .action-buttons button {
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Product Id</th>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                <td><?= htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <img src="<?= $product['product_imagelink'] ?>" alt="<?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?>">
                    </td>
                    <td><?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($product['product_description'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($product['product_price'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($product['product_quantity'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="action-buttons">
                        <form method="GET" action="updateProduct.php">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit">Update</button>
                        </form>
                        <form method="POST" action="deleteProduct.php">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id'], ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="addProduct.php">Add Product</a>
</body>
</html>
