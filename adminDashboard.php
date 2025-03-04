<?php
include 'config.php';
include 'adminNavbar.php';

session_start();

if($_SESSION['user_id'] != 1016){
    echo "Access Denied. Only Admin has to manage products";
    exit();
}

?>
<html>
    <body>
    <div class="container mt-5">
        <div class="row mt-5">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <?php

                        $query = "SELECT COUNT(*) AS total_orders FROM orders";
                        $result = $conn->query($query);
                        $row = $result->fetch_assoc();
                        $totalOrders = $row['total_orders'] ?? 0; 
                        ?>
                        <p class="display-4"><?= $totalOrders ?></p>
                        <a href="viewOrders.php" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <?php
                        
                        $query = "SELECT COUNT(*) AS total_users FROM users WHERE user_id != 1016";
                        $result = $conn->query($query);
                        $row = $result->fetch_assoc();
                        $totalOrders = $row['total_users'] ?? 0;
                        ?>
                        <p class="display-4"><?= $totalOrders ?></p>
                        <a href="viewUsers.php" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Revenue</h5>
                        <?php
                        
                        $query = "SELECT SUM(total_price) AS total_price FROM orders";
                        $result = $conn->query($query);
                        $row = $result->fetch_assoc();
                        $totalOrders = $row['total_price'] ?? 0;
                        ?>
                        <p class="display-4">₹<?=$totalOrders ?>/-</p>
                        <a href="viewRevenue.php" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>