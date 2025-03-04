<?php
include 'config.php';
include 'navbar.php';

$firstname = $lastname = $password = $confirm_password = $email = $gender = $contact = "";
$firstname_err = $lastname_err = $password_err = $confirm_password_err = $email_err = $gender_err = $contact_err = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["firstname"])) {
        $firstname_err = "*First name is required.";
    } else {
        $firstname = trim($_POST["firstname"]);
    }

    if (empty($_POST["lastname"])) {
        $lastname_err = "*Last name is required.";
    } else {
        $lastname = trim($_POST["lastname"]);
    }

    if (empty($_POST["email"])) {
        $email_err = "*Email is required.";
    } else {
        $email = trim($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "*Invalid email format.";
        }
    }

    if (empty($_POST["password"])) {
        $password_err = "*Password is required.";
    } else {
        $password = trim($_POST["password"]);
        if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[@$!%*?&#]/", $password)) {
            $password_err = "*Password must be at least 8 characters long, include one letter, one number, and one special character.";
        }
    }

    if (empty($_POST["confirm_password"])) {
        $confirm_password_err = "*Confirm password is required.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password !== $confirm_password) {
            $confirm_password_err = "*Passwords do not match.";
        }
    }

    if (empty($_POST["gender"])) {
        $gender_err = "*Gender is required.";
    } else {
        $gender = trim($_POST["gender"]);
    }

    if (empty($_POST["contact"])) {
        $contact_err = "*Contact number is required.";
    } else {
        $contact = trim($_POST["contact"]);
        if (!is_numeric($contact) || strlen($contact) != 10) {
            $contact_err = "*Contact number must be a 10-digit number.";
        }
    }

    if (empty($firstname_err) && empty($lastname_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($gender_err) && empty($contact_err)) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "CALL AddUser('$firstname', '$lastname', '$hashedPassword', '$email', '$gender', '$contact')";

        $query = mysqli_query($conn, $sql);

        if ($query) {
            $successMessage = "User added successfully!";
            header("Location: login.php?success=" . urlencode($successMessage));
            exit();
        } else {
            echo "Error occurred: " . mysqli_error($conn);
        }
    }
}


?>

<html>
<head>
    <title>EasyCart - Register</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 400px;
            margin-top: 10px;
        }

        h2 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .form-group-full {
            margin-bottom: 15px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            color: #555555;
            margin-bottom: 5px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
            background-color: #f9f9f9;
            outline: none;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            border-color: #007BFF;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Create Account</h2>

            <div class="form-group">
                <div style="width: 48%;">
                    <label>First Name:</label>
                    <input type="text" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>">
                    <span class="error-message"><?php echo $firstname_err; ?></span>
                </div>
                <div style="width: 48%;">
                    <label>Last Name:</label>
                    <input type="text" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>">
                    <span class="error-message"><?php echo $lastname_err; ?></span>
                </div>
            </div>

            <div class="form-group-full">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error-message"><?php echo $email_err; ?></span>
            </div>

            <div class="form-group">
                <div style="width: 48%;">
                    <label>Password:</label>
                    <input type="password" name="password">
                    <span class="error-message"><?php echo $password_err; ?></span>
                </div>
                <div style="width: 48%;">
                    <label>Confirm Password:</label>
                    <input type="password" name="confirm_password">
                    <span class="error-message"><?php echo $confirm_password_err; ?></span>
                </div>
            </div>

            <div class="form-group">
                <div style="width: 48%;">
                    <label>Gender:</label>
                    <select name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Other" <?php if ($gender == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                    <span class="error-message"><?php echo $gender_err; ?></span>
                </div>
                <div style="width: 48%;">
                    <label>Contact:</label>
                    <input type="tel" name="contact" value="<?php echo htmlspecialchars($contact); ?>">
                    <span class="error-message"><?php echo $contact_err; ?></span>
                </div>
            </div>
            <input type="submit" name="register" value="Register">
        </form>
    </div>
</body>
</html>          
