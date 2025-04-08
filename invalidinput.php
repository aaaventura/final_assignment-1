<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/
session_start();

if(isset($_SESSION['errors'])){
    $errors = $_SESSION['errors'];
}


//print_r($errors);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Homepage</title>
</head>
<body>

    <?php include 'header.php' ?>


    <main>
        <h1> Invalid Input. </h1>
        <ul>
        <?php foreach ($errors as $error): ?>
            <li> <?= $error ?></li> 
        <?php endforeach; ?>
        </ul>
        
        <p> Return to Home: <a href="index.php">Here</a></p>

    </main>

    <?php include 'footer.php' ?>
</body>
</html>



