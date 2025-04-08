<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/

session_start();

function generateCaptcha() {

    $characters = 'ABDEFGHJKLMNPQRTWYabdefghijkmnpqrtwy23456789';
    $captcha = '';


    for ($i = 0; $i < 6; $i++) {
        $captcha .= $characters[rand(0, strlen($characters) - 1)];
    }

    $_SESSION['captcha'] = $captcha;

    return $captcha;
}

generateCaptcha();
    
header("Content-type: image/png");


// image and background
$image = imagecreate(200, 60);
$background = imagecolorallocate($image, 255, 255, 255); 
$textColor = imagecolorallocate($image, 0, 0, 0); 

imagefilledrectangle($image, 0, 0, 200, 60, $background);


// Add noise - random dots
for ($i = 0; $i < 1500; $i++) {
    $dotColor = imagecolorallocate($image, rand(150, 255), rand(150, 255), rand(150, 255));
    imagesetpixel($image, rand(0, 200), rand(0, 60), $dotColor);
}



// draw captcha
$captcha = $_SESSION['captcha'];
$x = 20; 

for ($i = 0; $i < strlen($captcha); $i++) {
    $letter = $captcha[$i];


    $y = rand(10, 30); 

    imagestring($image, 5, $x, $y, $letter, $textColor);

    $x += rand(20, 30); 

}

// generate
imagepng($image);
imagedestroy($image);



?>