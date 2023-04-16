<?php
//require('authenticateAdmin.php');
require('connect.php');
require __DIR__ . DIRECTORY_SEPARATOR . 'php-image-resize-master' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'ImageResize.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'php-image-resize-master' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'ImageResizeException.php';
use \Gumlet\ImageResize;


// file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
// Default upload path is an 'uploads' sub-folder in the current folder.
function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
    $current_folder = dirname(__FILE__);
    
    // Build an array of paths segment names to be joins using OS specific slashes.
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
    
    // The DIRECTORY_SEPARATOR constant is OS specific.
    return join(DIRECTORY_SEPARATOR, $path_segments);
 }

 function file_is_an_image($temporary_path, $new_path) {
    $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
    $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
    
    $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
    $actual_mime_type        = getimagesize($temporary_path)['mime'];
    
    $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
    $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
    
    return $file_extension_is_valid && $mime_type_is_valid;
}


if(!isset($_GET['id'])){
    $query = "SELECT * FROM products";
    $statement = $db->prepare($query);
    $statement->execute();
}

if(!empty($_GET['id'])){
    $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

    $query = "SELECT * FROM products WHERE product_id = {$id}";
    $statement = $db->prepare($query);
    $statement->execute();
    $row = $statement->fetch();

    // Fetch categories from database
$query = "SELECT * FROM categories";
$statement = $db->prepare($query);
$statement->execute();
$categories = $statement->fetchAll();
    /*
// Get the product ID from the URL parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    // Handle invalid ID
}


if (!$row) {
        $errorFlag = true;
        $errorMessage = "Product not found.";
    }
} else {
    $errorFlag = true;
    $errorMessage = "Product ID is missing.";
}
    */
}

$errorFlag = false;
$errorMessage = "";

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize
    $product_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    //$availability = isset($_POST['available']) ? $_POST['available'] : (isset($_POST['not-available']) ? $_POST['not-available'] : '');
    $category_id = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);

    //$availability = $availability == "available" ? 1 : 0;
    $availability = intval($_POST['availability']);
    echo $availability;

    // Validate form data
    if (empty($name) || empty($description) || empty($price) || ($availability !== 0 && $availability !== 1)) {
        $errorFlag = true;
        $errorMessage = "All fields are required.";
    } elseif (!is_numeric($price)) {
        $errorFlag = true;
        $errorMessage = "Price must be numeric.";
    } else {
        if (isset($_POST['update-btn'])) {
        // Update product details in the database


       if(isset($_POST['upload-image'])){
            $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
            $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);

            if ($image_upload_detected) { 
                $image_filename        = $_FILES['image']['name'];
                $temporary_image_path  = $_FILES['image']['tmp_name'];
                $new_image_path        = file_upload_path($image_filename);
            
                if (file_is_an_image($temporary_image_path, $new_image_path)) {
                    // Save the uploaded image to the uploads folder

                    // Load the image using php-image-resize
                    $image = new ImageResize($temporary_image_path);

                    // Resize the image to a width of 500 pixels and a height of 500 pixels, maintaining the aspect ratio
                    $image->resizeToBestFit(500, 500); // width, height

                    // Save the resized image to the new path
                    $image->save($new_image_path);

                    // Move the resized image to the new path
                    //move_uploaded_file($new_image_path, $new_path);
                    //move_uploaded_file($temporary_image_path, $new_image_path);

                    

                    // Add the image filename to the images table
                    
                    $query1 = "INSERT INTO images (image_filename) VALUES (:imagefilename)";
                    $statement1 = $db->prepare($query1);
                    $statement1->bindValue(':imagefilename', $image_filename);
                    $statement1->execute();
                    $imageID = $db->lastInsertId();
            
                    $query = "UPDATE products SET category_id = :category_id, image_id = :imageID, product_name = :pname, product_description = :pdescription, product_price = :pprice, product_availability = :pavailability WHERE product_id = :product_id";
                    $statement = $db->prepare($query);
                    $statement->bindValue(':imageID', $imageID);
                    $statement->bindValue(':pname', $name);        
                    $statement->bindValue(':pdescription', $description);
                    $statement->bindValue(':pprice', $price);
                    $statement->bindValue(':pavailability', $availability);
                    $statement->bindValue(':product_id', $product_id, PDO::PARAM_INT);
                    $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
                    $statement->execute();
                    //echo "done updating.";

                    
                    // Redirect to index page
                    header('Location: editProduct.php');
                } else{
                    $errorFlag = true;
                    $errorMessage = "Sorry, the file you uploaded is not an image. Please upload an image file (JPG, PNG, JPEG,GIF).";
                }
            } else{
                $errorFlag = true;
                $errorMessage = "Errors: " . $_FILES['image']['error'];
            }

       } else if(isset($_POST['delete-image'])){
            $imageID = $_POST['imageID'];
            // Delete the image from the filesystem
            $queryImage = "SELECT * FROM images WHERE image_id = :imageID";
            $statement3 = $db->prepare($queryImage);
            $statement3->bindValue(':imageID', $imageID);
            $statement3->execute();
            $image = $statement3->fetch();

            unlink(file_upload_path($image['image_filename']));
                
            // Delete the image from the database
            $query = "DELETE FROM images WHERE image_id = :imageID";
            $statement2 = $db->prepare($query);
            $statement2->bindValue(':imageID', $imageID);
            $statement2->execute();
        
            // Remove the image ID from the page record
            $imageID = 1;
            $query1 = "UPDATE products SET category_id = :category_id, image_id = :imageID, product_name = :pname, product_description = :pdescription, product_price = :pprice, product_availability = :pavailability WHERE product_id = :product_id";
            $statement1 = $db->prepare($query1);
            $statement1->bindValue(':imageID', $imageID);
            $statement1->bindValue(':pname', $name);        
            $statement1->bindValue(':pdescription', $description);
            $statement1->bindValue(':pprice', $price);
            $statement1->bindValue(':pavailability', $availability);
            $statement1->bindValue(':product_id', $product_id, PDO::PARAM_INT);
            $statement1->bindValue(':category_id', $category_id, PDO::PARAM_INT);
            $statement1->execute();
            //echo "done updating.";

            
            // Redirect to index page
            header('Location: editProduct.php');

       } else {
            $query = "UPDATE products SET category_id = :category_id, product_name = :pname, product_description = :pdescription, product_price = :pprice, product_availability = :pavailability WHERE product_id = :product_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':pname', $name);        
            $statement->bindValue(':pdescription', $description);
            $statement->bindValue(':pprice', $price);
            $statement->bindValue(':pavailability', $availability);
            $statement->bindValue(':product_id', $product_id, PDO::PARAM_INT);
            $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
            $statement->execute();
            //echo "done updating.";

            
            // Redirect to index page
            header('Location: editProduct.php');
       }
            
        exit;
        } else if(isset($_POST['delete-btn'])){
            $query = "DELETE FROM products WHERE product_id = :product_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':product_id', $product_id, PDO::PARAM_INT);
            $statement->execute();
            //echo "done deleting";   
            //header('Location: index.php');
            //exit;   
            header('Location: editProduct.php');
        }
    }
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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
</head>
<body>
    <a href="index.php">Home</a>
    <?php if(isset($_GET['id'])): ?>
    <form action="editProduct.php" method="post"  enctype='multipart/form-data'>
        <input type="hidden" name="id" value="<?= $row['product_id'] ?>">

        
            <label for="category">Category</label>
            <br>
            <select name="category" id="category">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['category_id'] ?>" <?= $category['category_id'] === $row['category_id'] ? 'selected' : '' ?>>
                        <?= $category['category_name'] ?>
                    </option>
                <?php endforeach ?>
            </select>
        

        <label for="name">Product Name</label>
        <input type="text" name="name" value="<?= $row['product_name'] ?>">

        <label for="description">Product Description</label>
        <textarea name="description"><?= $row['product_description'] ?></textarea>

        <label for="price">Price</label>
        <input type="number" name="price" step="0.01" min="0" value="<?= $row['product_price'] ?>">

        <label for="availability">Availability</label>
        <input type="radio" name="availability" value="1" <?php echo $row['product_availability'] == 1 ? 'checked' : ''; ?>>Available
        <input type="radio" name="availability" value="0" <?php echo $row['product_availability'] == 0 ? 'checked' : ''; ?>>Not Available

        <br>
        <?php if($row['image_id'] == 1): ?>
        <label for="image">Image:</label>
        <input type="file" name="image" id="image" value="">
        <br>
        
        <input type="checkbox" name="upload-image" value=""> <!-- checkbox needs to be selected to add image -->
        <label for="upload-image"><i>checkbox needs to be selected to add image</i></label>
        <?php else: ?>
        <input type="checkbox" name="delete-image" value=""> <!-- checkbox needs to be selected to add image -->
        <label for="delete-image"><i>checkbox needs to be selected to delete image</i></label>
        <input type="hidden" name="imageID" id="imageID" value="<?= $row['image_id'] ?>">
        <?php endif ?>
        <p>
            <input type="submit" name="update-btn" value="Update">
            <input type="submit" name="delete-btn" value="Delete" onclick="return confirm('Are you sure you wish to delete this product?')">
        </p>
    </form>
    <?php else: ?>
        <?php while($row = $statement->fetch()): ?>
    <div style="border:1px solid black;">
        <a href="editProduct.php?id=<?= $row['product_id'] ?>">edit</a>
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
    <?php endif?>

    <?php if($errorFlag): ?>
        <p><?= $errorMessage ?></p>
    <?php endif ?>
</body>
</html>
