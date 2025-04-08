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


// todo
// we gotta figure out how to make captcha validation on client side.
// then we have to... do css? 
//actually, right now, i'm gonna see what I can do that's realistic withing the scope. 
// if I can't add any more features within a realistic time, then I'll do styles and 
// spend the rest of my time making this thing look nice.
// another thing i can add is the search bar at the top of every page. that's something I completely forgot to add
// it won't be hard to implement either, I can put it in the header.php and have it redirect to the search page like always.
// it can be easy as a post too which is already the input I am taking in. 


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



        <?php if(isset($sessionName)): ?>

            <p>You are logged in as: <?= $sessionName?></p>
            <p><a href="logout.php">log out</a></p>

        <?php else:?>
            <script src="captcha.js"></script>
            <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <canvas id="captcha"></canvas>
            <input id="textBox" type="text" name="textBox" required>

            <button type="submit">Login</button>
        </form>


        <div>
            <p>No account?</p>
            <p><a href="createaccount.php">Create Account</a></p>
        </div>

        <?php endif; ?>

    </main>

   <?php include 'footer.php' ?>
    
</body>
</html>




