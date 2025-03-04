<?php
include 'config.php';
include 'adminNavbar.php';

$query = "SELECT * FROM orders ORDER BY order_date DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

?>
<html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            h1 {
                text-align: center;
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
            
        </style>
        
    </head>
    <body>
    <table>
        <thead>
            <tr>
                <th>Order Id</th>
                <th>User Id</th>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()){
                ?>
                <tr>
                    <td> <?php echo $row['order_id'] ?> </td>
                    <td> <?php echo $row['user_id'] ?> </td>
                    <td> <?php echo $row['product_name'] ?> </td>
                    <td> <?php echo $row['product_price'] ?> </td>
                    <td> <?php echo $row['quantity'] ?> </td>
                    <td> <?php echo $row['total_price'] ?> </td>
                    <td> <?php echo $row['order_date'] ?> </td>
                    <td> Order Received </td>
                </tr>
        <?php } ?>
        </tbody>     
    </table>                  
    </body>
</html>