<?php
require('connect.php');

if(!isset($_GET['id'])){
    $query = "SELECT * FROM users";
    $statement = $db->prepare($query);
    $statement->execute();
} else if(isset($_GET['id'])){
    $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

    $query = "SELECT * FROM users WHERE user_id = {$id}";
    $statement = $db->prepare($query);
    $statement->execute();
    $row = $statement->fetch();
}

$errorFlag = false;
$errorMessage = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize
    $user_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'user-email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $firstName = filter_input(INPUT_POST, 'user-firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastName = filter_input(INPUT_POST, 'user-lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $userRole = filter_input(INPUT_POST, 'user-role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // Validate form data
    if (empty($username) || empty($email) || empty($firstName) || empty($lastName) || empty($userRole)) {
        $errorFlag = true;
        $errorMessage = "All fields are required.";
    } else {
        if (isset($_POST['update-btn'])) {
        // Update product details in the database
            //$changedPassword = $_POST['user-password'];
            
            if(isset($_POST['change-password'])){
                $changedPassword = $_POST['user-password'];
                $hashedPassword = password_hash($changedPassword, PASSWORD_DEFAULT);

                $query = "UPDATE users SET user_name = :username, user_email = :useremail, user_firstname = :userfirstname, user_lastname = :userlastname, user_password = :userpassword, user_role = :userrole WHERE user_id = :userid";
                $statement = $db->prepare($query);
                $statement->bindValue(':username', $username);        
                $statement->bindValue(':useremail', $email);
                $statement->bindValue(':userfirstname', $firstName);
                $statement->bindValue(':userlastname', $lastName);
                $statement->bindValue(':userpassword', $hashedPassword);
                $statement->bindValue(':userrole', $userRole);
                $statement->bindValue(':userid', $user_id, PDO::PARAM_INT);
                $statement->execute();
                //echo "done updating.";

                
                // Redirect to index page
                header('Location: editUser.php');
                exit;
            } else{

            $query = "UPDATE users SET user_name = :username, user_email = :useremail, user_firstname = :userfirstname, user_lastname = :userlastname, user_role = :userrole WHERE user_id = :userid";
            $statement = $db->prepare($query);
            $statement->bindValue(':username', $username);        
            $statement->bindValue(':useremail', $email);
            $statement->bindValue(':userfirstname', $firstName);
            $statement->bindValue(':userlastname', $lastName);
            //$statement->bindValue(':userpassword', $changedPassword);
            $statement->bindValue(':userrole', $userRole);
            $statement->bindValue(':userid', $user_id, PDO::PARAM_INT);
            $statement->execute();
            //echo "done updating.";

            
            // Redirect to index page
            header('Location: editUser.php');
            exit;}
        } else if(isset($_POST['delete-btn'])){
            $query = "DELETE FROM users WHERE user_id = :userid";
            $statement = $db->prepare($query);
            $statement->bindValue(':userid', $user_id, PDO::PARAM_INT);
            $statement->execute();
            //echo "done deleting";   
            //header('Location: index.php');
            //exit;   
            header('Location: editUser.php');
        }
    }
}

?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Users</title>
</head>
<style>
        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type=text], input[type=number] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        input[type=radio] {
            margin-right: 10px;
        }

        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
<body>
    <a href="index.php">Home</a>
    <?php if(isset($_GET['id'])): ?>
    <form action="editUser.php" method="post">
        <input type="hidden" name="id" value="<?= $row['user_id'] ?>">

        <label for="username">Userame</label>
        <input type="text" name="username" value="<?= $row['user_name'] ?>">

        <label for="user-eamil">Email</label>
        <input type="email" name="user-email" value="<?= $row['user_email'] ?>">

        <label for="user-firstname">First Name</label>
        <input type="text" name="user-firstname" value="<?= $row['user_firstname'] ?>">

        <label for="username">Last Name</label>
        <input type="text" name="user-lastname" value="<?= $row['user_lastname'] ?>">

        <label for="user-role">User Role</label>
        <input type="text" name="user-role" value="<?= $row['user_role'] ?>">


        <label for="user-password">Change Password</label>
        <input type="password" name="user-password" id="user-password" value="">
        <input type="checkbox" name="change-password"> <!-- checkbox needs to be selected to change the password -->
        <label for="change-password"><i>checkbox needs to be selected to change the password</i></label>
        <p>
            <input type="submit" name="update-btn" value="Update">
            <input type="submit" name="delete-btn" value="Delete" onclick="return confirm('Are you sure you wish to delete this user?')">
        </p>
    </form>
    <?php else: ?>
        <?php while($row = $statement->fetch()): ?>
            
            <div style="border:1px solid black;">
                <a href="editUser.php?id=<?= $row['user_id'] ?>">edit</a>
                <h2>Name: <?= $row['user_name'] ?></h2>
                <p>Email: <?= $row['user_email'] ?></p>
                <p>First Name: <?= $row['user_firstname'] ?></p>
                <p>Last Name: <?= $row['user_lastname'] ?></p>
                <p>User Role: <?= $row['user_role'] ?></p>
            </div>
        
        <?php endwhile ?>
    <?php endif?>

    <?php if($errorFlag): ?>
        <p><?= $errorMessage ?></p>
    <?php endif ?>

</body>
</html>