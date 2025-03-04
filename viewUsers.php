<?php
include 'config.php';
include 'adminNavbar.php';

$query = "SELECT * FROM users WHERE user_id != 1016";
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
                <th>User Id</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
                <th>Gender</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($result)>0 )
            while( $row = $result->fetch_assoc()) {
        ?>
                <tr>
                    <td><?php echo $row['user_id'] ?></td>
                    <td><?php echo $row['firstname'] ?></td>
                    <td><?php echo $row['lastname'] ?></td>
                    <td><?php echo $row['email'] ?></td>
                    <td><?php echo $row['gender'] ?></td>
                </tr>
           <?php }?>
        </tbody>
    </table>
</html>