<?php
//require('authenticateAdmin.php');
require('connect.php');

// Initialize error flag and message
$errorFlag = false;
$errorMessage = "";

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form data and sanitize
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);



    // Validate form data
    if (empty($name) || empty($description)) {
            $errorFlag = true;
            $errorMessage = "All fields are required.";
            //echo "one of them is not set";
        } else {
        // Insert new product into database
        $query = "INSERT INTO categories (category_name, category_description) VALUES (:cname, :cdescription)";
        $statement = $db->prepare($query);
        $statement->bindValue(':cname', $name);        
        $statement->bindValue(':cdescription', $description);
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
    <title>Add Categories</title>
</head>
<body>
    <a href="index.php">Home</a>
    <form action="addCategory.php" method="post">
    <fieldset>
        <legend>Add New Category</legend>
        <p>
            <label for="name">Name</label>
            <br>
            <input type="text" name="name" id="name" value="<?= isset($_POST['name']) ? $_POST['name'] : '' ?>" size="80">
        </p>
        <p>
            <label for="description">Description</label>
            <br>
            <input type="text" name="description" id="description" value="<?= isset($_POST['description']) ? $_POST['description'] : '' ?>" size="80">
        </p>


        <p>
            <input type="submit" name="post-btn" value="Add Category">
        </p>
    </fieldset>
    </form>
    <?php if($errorFlag): ?>
        <p><?= $errorMessage ?></p>
    <?php endif ?>

</body>
</html>
