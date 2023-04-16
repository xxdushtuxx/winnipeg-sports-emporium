<?php
require('connect.php');
$errorFlag = false;

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password-confirm'];



    // Check if passwords match
    if ($password !== $passwordConfirm) {
        $errorFlag = true;
        $errorMessage = 'Passwords do not match. Please try again.';
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        
        $query = "INSERT INTO users (user_name, user_email, user_firstname, user_lastname, user_password) VALUES (:uname, :uemail, :ufirstname , :ulastname, :upassword)";
        $statement = $db->prepare($query);
        $statement->bindValue(':uname', $username);        
        $statement->bindValue(':uemail', $email);
        $statement->bindValue(':ufirstname', $firstName);
        $statement->bindValue(':ulastname', $lastName);
        $statement->bindValue(':upassword', $hashedPassword);
        $statement->execute();

        // Redirect to login page
        header('Location: login.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sign Up Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            margin-top: 50px;
        }
        form {
            background-color: #ffffff;
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.3);
        }
        label {
            display: inline-block;
            margin: 10px 0px;
        }
        input[type="text"], input[type="email"], input[type="tel"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #3e8e41;
        }
        .signup-link {
            text-align: center;
            margin-top: 20px;
        }
        .signup-link a {
            color: #4CAF50;
            text-decoration: none;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Sign Up Form</h1>
    <form method="post" action="signup.php">
        <label for="firstName">First Name:</label>
        <input type="text" name="firstName" id="firstName" required>

        <label for="lastName">Last Name:</label>
        <input type="text" name="lastName" id="lastName" required>

        <label for="email">Email Address:</label>
        <input type="email" name="email" id="email" required>

        <label for="phone">Phone #:</label>
        <input type="tel" name="phone" id="phone" required>

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="password">Password confirm:</label>
        <input type="password" name="password-confirm" id="password-confirm" required>

        <input type="submit" value="Sign Up">

    </form>
    <div class="signup-link">Already have an account? <a href="login.php">Log in</a></div>

    <?php if($errorFlag): ?>
        <?= $errorMessage ?>
    <?php endif ?>
</body>
</html>
