<html>
<head>
    <title>EasyCart - Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            margin: 50px auto;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: white;
            color: #000;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <?php
        include 'config.php';
        include 'navbar.php';

        $error_message = "";

        if (isset($_POST['login'])) {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $query = "SELECT * FROM users WHERE `email` = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $row = $result->fetch_assoc();
                    if (password_verify($password, $row['password'])) {
                        session_start();
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['user_id'] = $row['user_id'];

                        if (($row['email'] === 'admin@gmail.com') || ($row['user_id'] === 1016) ){
                            header("Location: adminDashboard.php");
                        } else {
                            header("Location: display.php");
                        }
                        exit();
                    } else {
                        $error_message = "Invalid Username or Password";
                    }
                } else {
                    $error_message = "Invalid Username or Password";
                }
            }
        }
    ?>

    <div class="login-container">
        <form action="" method="post">
            <?php if (!empty($error_message)) : ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <h2>Login</h2>
            Email: <input type="text" name="email" required><br>
            Password: <input type="password" name="password" required><br>
            <input type="submit" name="login" value="Login"><br>
            <center>Don't have an account? <a href="register.php">Sign Up</a></center>
        </form>
    </div>
</body>
</html>
