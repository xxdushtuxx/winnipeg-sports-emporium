<?php
//require('authenticateAdmin.php');
require('connect.php');

// Initialize error flag and message
$errorFlag = false;
$errorMessage = "";


// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form data and sanitize
    $user_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'user-email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $firstName = filter_input(INPUT_POST, 'user-firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastName = filter_input(INPUT_POST, 'user-lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $userRole = filter_input(INPUT_POST, 'user-role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['user-password'];
    $passwordConfirm = $_POST['password-confirm'];
   

    // Validate form data
    if (empty($username) || empty($email) || empty($firstName) || empty($lastName) || empty($userRole) ||empty($password) || empty($passwordConfirm)) {
        $errorFlag = true;
        $errorMessage = "All fields are required.";
    } if ($password !== $passwordConfirm) {
        $errorFlag = true;
        $errorMessage = 'Passwords do not match. Please try again.';
    } else {
        // Insert new product into database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (user_name, user_email, user_firstname, user_lastname, user_password, user_role) VALUES (:username, :useremail, :userfirstname, :userlastname, :userpassword, :userrole)";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);        
        $statement->bindValue(':useremail', $email);
        $statement->bindValue(':userfirstname', $firstName);
        $statement->bindValue(':userlastname', $lastName);
        $statement->bindValue(':userpassword', $hashedPassword);
        $statement->bindValue(':userrole', $userRole);
        $statement->execute();

        // Redirect to index page
        header('Location: index.php');
        exit;
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add products</title>
</head>
<body>
    <a href="index.php">Home</a>
    <form action="addUser.php" method="post">
    <fieldset>
        <legend>Add New User</legend>
        <p>
            <label for="username">Username</label>
            <br>
            <input type="text" name="username" id="username" value="">
        </p>
        <p>
            <label for="user-email">Email</label>
            <br>
            <input type="email" name="user-email" id="user-email" value="">
        </p>
        <p>
            <label for="user-firstname">First Name</label>
            <br>
            <input type="text" name="user-firstname" id="user-firstname" value="">
        </p>
        <p>
            <label for="user-lastname">First Name</label>
            <br>
            <input type="text" name="user-lastname" id="user-lastname" value="">
        </p>
        <p>
            <label for="user-password">Password</label>
            <br>
            <input type="password" name="user-password" id="user-password" value="">
        </p>
        <p>
            <label for="password-confirm">Re-type Password</label>
            <br>
            <input type="password" name="password-confirm" id="password-confirm" value="">
        </p>
        <p>
            <label for="user-role">User Role</label>
            <br>
            <input type="text" name="user-role" id="user-role" value="" placeholder=" Type 'admin' or 'user'">
        </p>
        <p>
            <input type="submit" name="post-btn" value="Add User">
        </p>
    </fieldset>
    </form>
    <?php if($errorFlag): ?>
        <p><?= $errorMessage ?></p>
    <?php endif ?>

    <a href="categories.php">categories CUD</a>
</body>
</html>
