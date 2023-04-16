<?php


require('connect.php');
session_start();


    $query = "SELECT * FROM products ORDER BY product_price ASC";
    $statement = $db->prepare($query);
    $statement->execute();



?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winnipeg Sports Emporium</title>
    <link rel="stylesheet" href="./style.css">
    
</head>
<body>
    


    <li><a href="index.php">Home</a></li>
    <li><a href="products.php">products</a></li>
    

    

    <ul style="background-color:yellow;">
            <a href="products.php?sort=name">Name</a>
            <a href="products.php?sort=price">Price</a>
            <a href="products.php?sort=posted">Posting Time</a>
    </ul>


            <?php while($row = $statement->fetch()): ?>
            <div style="border:1px solid black;">
                <p><a href="products.php?id=<?= $row['product_id'] ?>"><?= $row['product_name'] ?></a>                               <?= $row['product_price'] ?> </p>
            </div>
            <?php endwhile ?>

    
    
    
</body>
</html>