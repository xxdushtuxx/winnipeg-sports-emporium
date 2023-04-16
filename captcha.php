<?php
session_start();

// Generate random 6-character code
$captcha = substr(md5(mt_rand()), 0, 6);

// Save CAPTCHA code in session
$_SESSION['captcha'] = $captcha;

// Create image
$image = imagecreate(100, 50);

// Set background color
$bg_color = imagecolorallocate($image, 255, 255, 255);

// Set text color
$text_color = imagecolorallocate($image, 0, 0, 0);

// Add text to image
imagestring($image, 5, 25, 18, $captcha, $text_color);

// Output image as PNG
header('Content-type: image/png');
imagepng($image);

// Clean up
imagedestroy($image);
?>
