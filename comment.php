<?php 
require('connect.php');

session_start();

$captchaFailure = false;
$message = "";

if(isset($_GET['id'])){
    $_SESSION['commentProductID'] = $_GET['id'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    if ( $_SESSION['captcha'] != $_POST['captcha']) {
        $_SESSION['commentName'] = $_POST['name'];
        $_SESSION['commentText'] = $_POST['comment'];
        $message = "Incorrect CAPTCHA. Please try again.";
        $captchaFailure = true;
        
    } else {
    if(isset($_SESSION['user_id'])){

        $productID = $_SESSION['commentProductID']; 
        $userID = $_SESSION['user_id']; 

        $sql = "SELECT * FROM users WHERE user_id = :userID";
        $istatement = $db->prepare($sql);
        $istatement->bindValue(':userID', $userID);
        $istatement->execute();

        $info = $istatement->fetch();

        $name = $info['user_firstname'] . ' ' . $info['user_lastname'];
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
        
        if(empty($comment)){
            echo "one is empty";
        }

        // Insert comment into database
        $query = "INSERT INTO comments (product_id, user_id, user_commentername, comment) VALUES (:productID, :userID, :uname, :comment)";
        $statement = $db->prepare($query);
        $statement->bindValue(':productID', $productID, PDO::PARAM_INT);
        $statement->bindValue(':userID', $userID, PDO::PARAM_INT);
        $statement->bindValue(':uname', $name);
        $statement->bindValue(':comment', $comment);
        $statement->execute();

        $_SESSION['commentName'] = "";
        $_SESSION['commentText'] = "";

    } else{
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
        
        if(strlen(trim($name)) === 0){
            $name = "Anonymous";
        }

        $productID = $_SESSION['commentProductID']; // replace with your product ID
        $userID = 0; // replace with your user ID

        // Insert comment into database
        $query = "INSERT INTO comments (product_id, user_id, user_commentername, comment) VALUES (:productID, :userID, :uname, :comment)";
        $statement = $db->prepare($query);
        $statement->bindValue(':productID', $productID, PDO::PARAM_INT);
        $statement->bindValue(':userID', $userID, PDO::PARAM_INT);
        $statement->bindValue(':uname', $name);
        $statement->bindValue(':comment', $comment);
        $statement->execute();

        $_SESSION['commentName'] = "";


}
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form method="post" action="comment.php">

    <?php if(!isset($_SESSION['user_id'])): ?>
    <label for="name">Name:</label>
    <input type="text" name="name" value="<?= $_SESSION['commentName'] ?>" >
    <?php endif ?>
    <br>
    <label for="comment">Comment:</label>
    <input type="text" name="comment" value="<?= $_SESSION['commentText'] ?>" required>
    
    <br>
        <label for="captcha">Please enter the CAPTCHA:</label>
        <br>
        <img src="captcha.php" alt="CAPTCHA">
        <br>
        <input type="text" name="captcha" required>
        <br>
    <input type="submit" value="Submit">
</form>

<?php if($captchaFailure): ?>
<p><?= $message ?></p>
    <?php endif ?>
</body>
</html>
