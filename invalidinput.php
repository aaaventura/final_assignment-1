<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-04-10
    Description: Invalid input validation complete

****************/

session_start();

if(isset($_SESSION['errors'])){
    $errors = $_SESSION['errors'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Invalid Input</title>
</head>
<body>
    <div id="page-border">
        <?php include 'header.php' ?>
            <main>
                <section id="section-container">
                    <h1> Invalid Input. </h1>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li> <?= $error ?></li> 
                        <?php endforeach; ?>
                    </ul>
                    <div id="link-section">
                        <a id="link-button" href="index.php">Return Home</a>
                    </div>
                </section>
            </main>
        <?php include 'footer.php' ?>
    </div>
</body>
</html>



