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
}



// the next thing I should figure out how to do is... create a library. I guess... which shouldn't be too hard.
// but first, honestly, I should change the database. i think... I really need to add more data like name and date created and shit liek that.
//after creating that, I should make categories, I guess? I don't really know what that is... but I don't think i have to do those things. butt also, 
// i think I should do as many of the things that I should. because I'm tight for time here. 
//I won't be able to implement everything. the next on the crud list is the library search. that's pretty much it. that's the next step... I can finish crud be the end of this week is i do my best.

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
                <li><a href="edit.php">Edit</a></li>
                <li><a href="logout.php">Log Out</a></li>
                <li><a href="adminpage.php">admin</a></li>
            </ul>
        </nav>
    </header>

    <main>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    </main>

    <footer>
        <p>&copy; 2025 My CMS. All rights reserved.</p>
    </footer>
    
</body>
</html>

