<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/
session_start();

require('connect.php');

if(isset($_SESSION['name'])){
    $sessionName = $_SESSION['name'];
}




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
    <div id="page-border">
        <?php include 'header.php' ?>


        
        <main>
            <?php if(isset($sessionName)): ?>

                <section id="section-container">
                    <p>You are logged in as: <?= $sessionName?></p>
                    <div id="log-out">
                        <a id="link-button" href="logout.php">log out</a>
                    </div>
                    
                </section>
                

            <?php else:?>
                
                <form id="login"action="login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>

                <img src="generatecaptcha.php" alt="Captcha Image">
                <input id="textBox" type="text" name="textBox" placeholder="Enter CAPTCHA" required>

                <button type="submit">Login</button>
            </form>


            <div>
                <p>No account?</p>
                <p><a href="createaccount.php">Create Account</a></p>
            </div>

            <?php endif; ?>

        </main>


        <?php include 'footer.php' ?>
    </div>

</body>
</html>




