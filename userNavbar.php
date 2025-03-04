<?php

include 'config.php';

$userId = intval($_SESSION['user_id']);
$query = "SELECT firstname FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i",$userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$username = $user ? htmlspecialchars($user['firstname'], ENT_QUOTES, 'UTF-8') : "Guest";
?>

<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">EasyCart</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="display.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="myOrders.php">My Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Logout</a>
                </li>
            </ul>
            <span class="navbar-text text-white me-3">Hi, <?= $username ?>!</span>
            <!-- <form class="d-flex" action="search.php" method="GET">
                <input class="form-control me-2" type="search" placeholder="Search Products" aria-label="Search" name="query">
                <button class="btn btn-outline-light" type="submit">Search</button>
            </form> -->
        </div>
    </div>
</nav>
</body>
</html>
