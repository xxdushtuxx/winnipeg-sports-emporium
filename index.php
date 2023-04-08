<?php

require('connect.php');
session_start();

if (isset($_SESSION['login_success']) && $_SESSION['login_success']) {
    // Display the success message
    echo "Login successful!";

    // Set the login success session variable to false to prevent the message from being displayed again
    $_SESSION['login_success'] = false;
}

$query = "SELECT * FROM products";
$statement = $db->prepare($query);
$statement->execute();

// Logout user if logout link is clicked
if (isset($_GET['logout'])) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php');
    exit;
    }
    // End session
    session_destroy();

    // Redirect to login page
    header('Location: login.php');
    exit;
}
//echo $statement->rowCount();


?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winnipeg Sports Emporium</title>
    <!--<link rel="stylesheet" href="./style.css">-->
    
</head>
<body>
    <h1>Winnipeg Sports Emporium</h1>
   <!-- <img src="./images/imagemain.jpg" alt="some image">-->
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">products</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="index.php?logout=true" style="<?php echo (($_SESSION['user_role'] == 'admin') || ($_SESSION['user_role'] == 'user')) ? 'visibility: visible;' : 'visibility: hidden;'; ?>">Logout</a></li>
        </ul>
    </nav><br>

    <div>
        <h2>categories</h2>
        <ul>
            <li><a href="productscategory.php?id=1">Boots</a></li>
            <li><a href="productscategory.php?id=2">Turfs</a></li>
            <li><a href="productscategory.php?id=5">Shorts</a></li>
            <li><a href="productscategory.php?id=6">sports uniform</a></li>
        </ul>
    </div>

    <!--<a href="admin-dashboard.php" >Admin</a>-->
    <a href="admin-dashboard.php" style="<?php echo ($_SESSION['user_role'] == 'admin') ? 'visibility: visible;' : 'visibility: hidden;'; ?>">Admin Page</a>


    <?php while($row = $statement->fetch()): ?>
    <div style="border:1px solid black;">
        <a href="edit.php?id=<?= $row['product_id'] ?>">edit</a>
        <h2>Name: <?= $row['product_name'] ?></h2>
        <p>Description: <?= $row['product_description'] ?></p>
        <p>Price: $<?= $row['product_price'] ?></p>
        <?php if($row['product_availability']): ?>
            <p>Available</p>
        <?php else: ?>
            <p>Not Available</p>
        <?php endif ?>
    </div>
        
    <?php endwhile ?>
    
    
</body>
</html>