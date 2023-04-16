<?php
//require('authenticateAdmin.php');
require('connect.php');

// Initialize error flag and message
$errorFlag = false;
$errorMessage = "";


    // Fetch categories from database
    $query = "SELECT * FROM categories";
    $statement = $db->prepare($query);
    $statement->execute();
    $categories = $statement->fetchAll();

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form data and sanitize
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    

    if ($price <= 0) {
        $errorFlag = true;
        
    }

    $category_id = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);


    $availability = isset($_POST['available']) ? $_POST['available'] : (isset($_POST['not-available']) ? $_POST['not-available'] : '');

    $availability = $availability == "available" ? 1 : 0;
    
   

    // Validate form data
    if (empty($name) || empty($description) || empty($price) || ($availability !== 0 && $availability !== 1) || $errorFlag) {
        echo $availability;
        echo $errorFlag;
        if($errorFlag){
            $errorMessage = "Price must be a positive number.";
        } else{
            $errorFlag = true;
            $errorMessage = "All fields are required.";
            //echo "one of them is not set";
        }
        
        
    } elseif (!is_numeric($price)){ //|| !is_numeric($availability)) {
        $errorFlag = true;
        $errorMessage = "Price and availability must be numeric.";
    } else {

        // Insert new product into database
        $query = "INSERT INTO products (category_id, product_name, product_description, product_price, product_availability) VALUES (:category_id, :pname, :pdescription, :pprice , :pavailability)";
        $statement = $db->prepare($query);
        $statement->bindValue(':pname', $name);        
        $statement->bindValue(':pdescription', $description);
        $statement->bindValue(':pprice', $price);
        $statement->bindValue(':pavailability', $availability);
        $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
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
    <form action="addProducts.php" method="post">
    <fieldset>
        <legend>Add New Product</legend>
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
            <label for="price">Price</label>
            <br>
            <input type="number" name="price" id="price" step="0.01" min="0" value="<?= isset($_POST['price']) ? $_POST['price'] : '' ?>" size="80">
         </p>
        <p>
        <p>
            <label for="category">Category</label>
            <br>
            <select name="category" id="category">
                <option value="0">Choose a category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['category_id'] ?>" >
                        <?= $category['category_name'] ?>
                    </option>
                <?php endforeach ?>
            </select>
        </p>
        <p>
            <fieldset id="availability-selector">
				<legend>Availability</legend>
					<input id="available-in-stock" name="available" type="radio" value="available"/>
					<label for="available">Available</label>
					<input id="not-available-in-stock" name="not-available" type="radio" value="not-available"/>
					<label for="not-available">Not Available</label>
					
			</fieldset>
            
        </p>
        <p>
        <form method='post' enctype='multipart/form-data'>
            <label for='image'>Image Filename:</label>
            <input type='file' name='image' id='image'>
            <input type='checkbox' name='submit' value='Upload Image'> <!-- checkbox needs to be selected to add image -->
        </form>
        </p>
        <p>
            <input type="submit" name="post-btn" value="Add Product">
        </p>
    </fieldset>
    </form>
    <?php if($errorFlag): ?>
        <p><?= $errorMessage ?></p>
    <?php endif ?>

    <a href="categories.php">categories CUD</a>
</body>
</html>
