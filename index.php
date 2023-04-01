<?php

require('connect.php');


$query = "SELECT * FROM products";
$statement = $db->prepare($query);
$statement->execute();


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
            
        </ul>
    </nav><br>
<!--<span id="admin"><a href="index.php">admin</a></span>-->
<a href="admin-dashboard.php" >Admin</a>
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