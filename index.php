<?php
session_start();
include 'config.php';
include 'navbar.php';

$query = "SELECT * FROM products";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<html>
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
                            <form action="login.php" method="POST">
                                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                <button type="submit" onclick='return alertLoginMessage()' class="btn btn-primary w-100">Place Order</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <script>
        function alertLoginMessage(){
            alert("Please Login to place an order!!");
            return true;
        }
    </script>
    </body>
</html>
