<?php
require('connect.php');

$query = "SELECT * FROM comments";
$statement = $db->prepare($query);
$statement->execute();
$rows = $statement->fetchAll();


function productName($id ,$db){
    $query1 = 'SELECT * FROM products WHERE product_id = :id';
    $statement1 = $db->prepare($query1);
    $statement1->bindValue(':id',$id,PDO::PARAM_INT);
    $statement1->execute();
    $info = $statement1->fetch();
    
    return $info['product_name'];
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentID = $_POST['id'];
    
    if (isset($_POST['update-btn'])) {
        $commentView = $_POST['view'];
         
            
            $query = "UPDATE comments SET comment_view = :commentView WHERE comment_id = :commentID";
                $statement = $db->prepare($query);
                $statement->bindValue(':commentView', $commentView);        
                $statement->bindValue(':commentID', $commentID, PDO::PARAM_INT);
                $statement->execute();

                header('Location: editComment.php');
        
        

    } else if(isset($_POST['delete-btn'])){
        $query = "DELETE FROM comments WHERE comment_id = :commentID";
            $statement = $db->prepare($query);
            $statement->bindValue(':commentID', $commentID, PDO::PARAM_INT);
            $statement->execute();
            //echo "done deleting";   
            //header('Location: index.php');
            //exit;   
            header('Location: editComment.php');
    }
}

?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderate Comments</title>
</head>
<body>
    <h1>Comments</h1>
    <?php foreach($rows as $row): ?>
        <div>
            <p>Product: <?= productName($row['product_id'], $db) ?></p>
            <p>Comment By: <?= $row['user_firstname'] ?> <?= $row['user_lastname'] ?></p>
            <p>Comment: <strong><?= $row['comment'] ?></strong> </p>
        </div>  
        

        <form action="editComment.php" method="POST">
            <input type="hidden" name="id" value="<?= $row['comment_id'] ?>">
            <fieldset>
                <label for="view">View</label>
                <br>
                <input type="radio" name="view" value="public" <?php echo $row['comment_view'] == 'public' ? 'checked' : ''; ?>>public
                <input type="radio" name="view" value="hide" <?php echo $row['comment_view'] == 'hide' ? 'checked' : ''; ?>>hide
            </fieldset>
            <?php if($row['comment_disemvowel'] == 'no'): ?>
                <input type="checkbox" name="disemvowel" value="yes">
                <label for="disemvowel">Disemvowel</label>
                <?php else: ?>
                    <p><i>Disemvowelled</i></p>
            <?php endif ?>
            <br>
            <input type="submit" name="update-btn" value="Update">
            <input type="submit" name="delete-btn" value="Delete">
        </form>
        <hr>
    <?php endforeach ?>
</body>
</html>