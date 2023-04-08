<?php

require('connect.php');
session_start();



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

    <label for="firstname">First Name:</label>
    <input type="text" name="firstname" required>
<br>
    <label for="lastname">Last Name:</label>
    <input type="text" name="lastname" required>

    <?php endif ?>
<br>
    <label for="comment">Comment:</label>
    <br>
    <textarea name="comment" required></textarea>
<br>
    <label for="captcha">Captcha:</label>
    <br>
    <img src="captcha.php" alt="CAPTCHA"><br>
    <input type="text" name="captcha" required>

    <input type="hidden" name="id" value="<?= $_SESSION['user_id'] ?>">
    <input type="hidden" name="id" value="0">
    <input type="submit" value="Submit">
</form>

</body>
</html>