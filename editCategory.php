<?php
//require('authenticateAdmin.php');
require('connect.php');

$errorFlag = false;
$errorMessage = "";

if(!isset($_GET['id'])){
    $query = "SELECT * FROM categories";
    $statement = $db->prepare($query);
    $statement->execute();
} else if(!empty($_GET['id'])){
    $category_id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

    $query = "SELECT * FROM categories WHERE category_id = {$category_id}";
    $statement = $db->prepare($query);
    $statement->execute();
    $row = $statement->fetch();
}



// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    // Get form data and sanitize
    $id = filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validate form data
    if (empty($name) || empty($description) ) {
        $errorFlag = true;
        $errorMessage = "All fields are required.";
    } else {
        if (isset($_POST['update-btn'])) {

        // Update product details in the database
        
            $query = "UPDATE categories SET category_name = :cname, category_description = :cdescription  WHERE category_id = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':cname', $name);        
            $statement->bindValue(':cdescription', $description);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();

          
            // Redirect to index page
            header('Location: editCategory.php');
            exit;
        } else if(isset($_POST['delete-btn'])){

            $query = "DELETE FROM categories WHERE category_id = :category_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':category_id', $id, PDO::PARAM_INT);
            $statement->execute();

            
            //header('Location: index.php');
            header('Location: editCategory.php');
            exit;
        }
    }
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Categories</title>
</head>
<body>
    <a href="index.php">Home</a>
    <a href="admin-dashboard.php" >Admin</a>
    <?php if(isset($_GET['id'])): ?>
        <form action="editCategory.php" method="post">
        <fieldset>
            <legend>Edit Category</legend>
            <p>
                <label for="name">Name</label>
                <br>
                <input type="text" name="name" id="name" value="<?= $row['category_name'] ?>" size="80">
            </p>
            <p>
                <label for="description">Description</label>
                <br>
                <input type="text" name="description" id="description" value="<?= $row['category_description'] ?>" size="80">
            </p>


            <p>
                <input type="hidden" name="id" value="<?= $row['category_id'] ?>">
                <input type="submit" name="update-btn" value="Update">
                <input type="submit" name="delete-btn" value="Delete" onclick="return confirm('Are you sure you wish to delete this category?')">
            </p>
        </fieldset>
        </form>
    <?php else: ?>
        <?php while($row = $statement->fetch()): ?>

        <div style="border:1px solid black;">
            <a href="editCategory.php?id=<?= $row['category_id'] ?>">edit</a>
            <h2>Name: <?= $row['category_name'] ?></h2>
            <p>Description: <?= $row['category_description'] ?></p>
        </div>
        
        <?php endwhile ?>
    <?php endif?>


    <?php if($errorFlag): ?>
        <p><?= $errorMessage ?></p>
    <?php endif ?>

</body>
</html>
