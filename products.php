<?php


require('connect.php');
session_start();

$queryforcat = "SELECT * FROM categories";
$statementforcat = $db->prepare($queryforcat);
$statementforcat->execute();
$catALL = $statementforcat->fetchAll();



if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    //if (!empty($searchTerm)) {
    $category = $_GET['category'];
    if($category == 1){
        $query = "SELECT * FROM products WHERE 
            product_name LIKE CONCAT('%', :search_term, '%') OR
            /*product_category LIKE CONCAT('%', :search_term, '%') OR  (this needs to be included)*/ 
            product_description LIKE CONCAT('%', :search_term, '%') OR
            product_price LIKE CONCAT('%', :search_term, '%')";

        $statement = $db->prepare($query);
        $statement->bindValue(':search_term', $searchTerm);
        $statement->execute();
        //echo $category;
    }else{
        
        $query = "SELECT * FROM products WHERE 
            (product_name LIKE CONCAT('%', :search_term, '%') OR
            product_description LIKE CONCAT('%', :search_term, '%') OR
            product_price LIKE CONCAT('%', :search_term, '%')) AND
            category_id = :category";

        $statement = $db->prepare($query);
        $statement->bindValue(':search_term', $searchTerm);
        $statement->bindValue(':category', $category);
        $statement->execute();
        echo $category;
    }
} else if(isset($_GET['sort'])){
    if($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'user'){
    $sorting_type = $_GET['sort'];
    echo "chosen sort " . $sorting_type;

    $col_name = "product_" . $sorting_type;
    $query = "SELECT * FROM products ORDER BY $col_name ASC";
    $statement = $db->prepare($query);
    $statement->execute();
    } else{

    $query = "SELECT * FROM products ORDER BY product_price ASC";
    $statement = $db->prepare($query);
    $statement->execute();
    }
} else if(!empty($_GET['id'])){
    $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

    $query = "SELECT * FROM products WHERE product_id = {$id}";
    $statement = $db->prepare($query);
    $statement->execute();
    $row = $statement->fetch();

    $query2 = "SELECT * FROM comments WHERE product_id = {$id} AND comment_view = 'public' ORDER BY comment_posted DESC";

    $statement2 = $db->prepare($query2);
    $statement2->execute();
    $comments = $statement2->fetchAll();

    $image_available = false;
    if($row['image_id'] > 1){
        $image_available = true;
        $queryImage = "SELECT * FROM images WHERE image_id = :imageID";
        $statement3 = $db->prepare($queryImage);
        $statement3->bindValue(':imageID', $row['image_id']);
        $statement3->execute();
        $image = $statement3->fetch();
    }
    
} else{
    $query = "SELECT * FROM products ORDER BY product_price ASC";
    $statement = $db->prepare($query);
    $statement->execute();
}


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
    <form action="products.php" method="get">
        <label for="seacrh">Search</label>
        <input type="text" name="search" placeholder="Search" value="<?= $_GET['search'] ?>">
        <br>
        <label for="category">Category:</label>
        <select id="category" name="category">
    <option value="1" <?php if ($category == 1) echo 'selected'; ?>>All categories</option>
    <?php foreach($catALL as $cat): ?>
        <option value="<?= $cat['category_id'] ?>" <?php if ($category == $cat['category_id']) echo 'selected'; ?> ><?= $cat['category_name'] ?></option>
    <?php endforeach ?>
</select>
<br>


        <button type="submit">Submit</button>
    </form>

    

    <ul style="background-color:yellow;">
            <a href="products.php?sort=name">Name</a>
            <a href="products.php?sort=price">Price</a>
            <a href="products.php?sort=posted">Posting Time</a>
    </ul>

    <!-- search results -->
    <?php if (isset($_GET['search'])): ?>
        <?php if ($statement->rowCount() > 0): ?>
            
            <?php while($row = $statement->fetch()): ?>
                <li><a href="products.php?id=<?= $row['product_id'] ?>"><?= $row['product_name'] ?></a></li>
            <?php endwhile ?>
            
        <?php else: ?>
            <p>No results found</p>
        <?php endif ?>

    <?php elseif(isset($_GET['id'])): ?>

        <!--<p>Get is set</p>-->

        <div style="border:1px solid black;">
        <h2>Name: <?= $row['product_name'] ?></h2>
            <p>
                <small>
                    <?php

                    $timestamp = strtotime($row['product_posted']);
                    if ($timestamp !== false) {
                        echo date('F d, Y  g:i A', $timestamp);
                    } else {
                        echo "Invalid date format";
                    }
  
                    ?>
                    
                </small>
            </p>
            

            <?php if($image_available): ?>
                <img src="./uploads/<?=$image['image_filename']?>" alt="<?= $image['image_description'] ?>">
            <?php endif ?>
            <p>Description: <?= $row['product_description'] ?></p>
            <p>Price: $<?= $row['product_price'] ?></p>
            <?php if($row['product_availability']): ?>
                <p>Available</p>
            <?php else: ?>
                <p>Not Available</p>
            <?php endif ?>


            <h3>Comments</h3>

            <?php foreach($comments as $comment): ?>
            <p>Name: <?= $comment['user_commentername'] ?></p>
            <p>Comment: <?= $comment['comment'] ?></p>
            <hr>
            <?php endforeach ?>

            <a href="comment.php?id=<?= $row['product_id'] ?>">Comment</a>
        </div>



    <?php else: ?>
        <!-- sorted results -->
        <?php if(isset($_GET['sort']) ): ?> 

            <?php while($row = $statement->fetch()): ?>
                <div style="border:1px solid black;">
                <p><a href="products.php?id=<?= $row['product_id'] ?>"><?= $row['product_name'] ?></a>                               <?= $row['product_price'] ?> </p>
                </div>
            <?php endwhile ?>
        
        <?php else: ?>

            <?php while($row = $statement->fetch()): ?>
            <div style="border:1px solid black;">
                <p><a href="products.php?id=<?= $row['product_id'] ?>"><?= $row['product_name'] ?></a>                               <?= $row['product_price'] ?> </p>
            </div>
            <?php endwhile ?>
        <?php endif ?>
    <?php endif ?>
    
    
    
</body>
</html>