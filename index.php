<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-04-10
    Description: index passed validdation

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
                    <p class="black-text">You are logged in!</p>
                    <div id="link-section">
                        <a id="link-button" href="logout.php">Log Out</a>
                    </div>
                </section>
            <?php else:?>
            <form id="login" action="login.php" method="POST">
                <h2>Please Log in</h2>
                <input class="text-input" id="username-input" type="text" name="username" placeholder="Username" required>
                <input class="text-input" id="password-input" type="password" name="password" placeholder="Password" required>
                <img id="captcha-image" src="generatecaptcha.php" alt="Captcha Image">
                <input class="text-input" id="textBox" type="text" name="textBox" placeholder="Enter CAPTCHA" required>
                <button type="submit">Login</button>
            </form>
            <section id="section-container">
                <p>No account?</p>
                <div id="link-section">
                    <a id="link-button" href="createaccount.php">Create Account</a>
                </div>
            </section>
            <?php endif; ?>
        </main>
        <?php include 'footer.php' ?>
    </div>
</body>
</html>




