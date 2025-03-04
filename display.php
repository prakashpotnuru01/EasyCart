<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?message=Please_login_to_view_products!');
    exit();
}

$userId = intval($_SESSION['user_id']);
include 'userNavbar.php';

$query = "SELECT * FROM products";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .quantity-control button {
            width: 30px;
            height: 30px;
            border: none;
            background-color: #f0f0f0;
            color: #333;
            font-weight: bold;
            font-size: 18px;
            cursor: pointer;
        }
        .quantity-control button:hover {
            background-color: #ddd;
        }
        .quantity-control input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
        #error-message {
            color: red;
            display: none;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="row g-3">
        <?php while ($row = $result->fetch_assoc()) {
            $productImagePath = 'uploads/' . htmlspecialchars($row['product_imagelink'], ENT_QUOTES, 'UTF-8');
            if (!file_exists($productImagePath)) {
                $productImagePath = 'uploads/default.jpg';
            }
        ?>
        <div class="col-md-3">
            <div class="card h-100">
                <img src="<?= $productImagePath ?>" class="card-img-top" alt="<?= $row['product_name'] ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8') ?></h5>
                    <p class="card-text"><?= htmlspecialchars($row['product_description'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="card-price"><strong>Price: ₹<?= htmlspecialchars($row['product_price'], ENT_QUOTES, 'UTF-8') ?>/-</strong></p>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary w-100" onclick="showOrderForm(<?= $row['product_id'] ?>, <?= htmlspecialchars($row['product_quantity'], ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($row['product_price'], ENT_QUOTES, 'UTF-8') ?>)">Place Order</button>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="orderForm" action="order.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Select Quantity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="product_id">
                    <input type="hidden" name="price" id="product_price">
                    <p id="stock-info"></p>
                    <p id="error-message">Stock is not available!</p>
                    <div class="quantity-control mb-3">
                        <button type="button" onclick="updateModalQuantity(-1)">-</button>
                        <input type="number" id="modalQuantity" name="quantity" value="1" min="1">
                        <button type="button" onclick="updateModalQuantity(1)">+</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Done</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let maxStock;

    function showOrderForm(productId, stock, price) {
        document.getElementById('product_id').value = productId;
        document.getElementById('product_price').value = price;
        document.getElementById('modalQuantity').value = 1;
        maxStock = stock;
        document.getElementById('stock-info').textContent = `Available Stock: ${stock} | Price: ₹${price}/unit`;
        document.getElementById('error-message').style.display = 'none'; 
        new bootstrap.Modal(document.getElementById('orderModal')).show();
    }

    function updateModalQuantity(delta) {
        const quantityInput = document.getElementById('modalQuantity');
        const errorMessage = document.getElementById('error-message');
        let newQuantity = parseInt(quantityInput.value) + delta;

        if (newQuantity >= 1 && newQuantity <= maxStock) {
            quantityInput.value = newQuantity;
            errorMessage.style.display = 'none'; 
        } else if (newQuantity > maxStock) {
            errorMessage.textContent = 'Quantity exceeds available stock!';
            errorMessage.style.display = 'block'; 
        } else if (newQuantity < 1) {
            errorMessage.textContent = 'Quantity must be at least 1!';
            errorMessage.style.display = 'block'; 
        } else if (newQuantity == 0) {
            errorMessage.textContent = 'Stock is not available!';
            errorMessage.style.display = 'block'; 
        }
    }


    document.getElementById('orderForm').addEventListener('submit', function(event) {
        const quantityInput = document.getElementById('modalQuantity');
        const quantity = parseInt(quantityInput.value);
        const errorMessage = document.getElementById('error-message');

        if (quantity > maxStock || maxStock === 0) {
            event.preventDefault(); 
            errorMessage.textContent = 'Out of Stock!';
            errorMessage.style.display = 'block'; 
        }
    });

</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>

