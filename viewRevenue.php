<?php
include 'config.php';
include 'adminNavbar.php';

$query = "SELECT DATE_FORMAT(order_date, '%Y-%m') AS month,
          SUM(total_price) AS total_revenue
          FROM orders
          GROUP BY DATE_FORMAT(order_date, '%Y-%m') 
          ORDER BY month;";

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
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($result)>0 )
            while( $row = $result->fetch_assoc()) {
        ?>
                <tr>
                    <td><?php echo $row['month'] ?></td>
                    <td><?php echo $row['total_revenue'] ?></td>
                </tr>        
           <?php }?>
        </tbody>
    </table>
</html>