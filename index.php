<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/
session_start();

require('connect.php');

if (isset($_SESSION['name'])) {
    echo "Logged in as: " . $_SESSION['name'];
} else {
    echo "Not logged in.";
    echo '<script src="captcha.js"></script>';
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

    <header>
        <h1><a href="index.php">Audio Library Database</a></h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="audiolibrary.php">Search Library</a></li> 
                <li><a href="artistpage.php">Artists Upload</a></li>
                
                <li><a href="adminpage.php">admin</a></li>
            </ul>
        </nav>
    </header>

    <main>

        <?php if (!isset($_SESSION['name'])): ?>
                    
                <?php endif; ?>

        <?php if(isset($_SESSION['name'])): ?>
            <p><a href="logout.php">log out</a></p>

        <?php else:?>
            <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <canvas id="captcha"></canvas>
            <input id="textBox" type="text" name="text" required>

            <button type="submit">Login</button>
        </form>


        <div>
            <p>No account?</p>
            <p><a href="createaccount.php">Create Account</a></p>
        </div>

        <?php endif; ?>

    

   
    
    </main>

    <footer>
        <p>&copy; 2025 My CMS. All rights reserved.</p>
    </footer>
    
</body>
</html>




