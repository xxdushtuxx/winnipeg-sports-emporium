<?php

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

        <label for="shippingAddress">Shipping Address:</label>
        <input type="text" name="shippingAddress" id="shippingAddress" required>

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Sign Up">

    </form>
    <div class="signup-link">Already have an account? <a href="login.php">Log in</a></div>
</body>
</html>
