<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-04-10
    Description: Access denied verified

****************/

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Access Denied</title>
</head>
<body>
    <div id="page-border">
        <?php include 'header.php' ?>
        <main>
            <h1> Access Denied.</h1>
            <p class="black-text"> You do not have permission to access this page.</p>
            <div id="link-section">
                <a id="link-button" href="index.php">Return Home</a>
            </div>
        </main>
        <?php include 'footer.php' ?>
    </div>
</body>
</html>



