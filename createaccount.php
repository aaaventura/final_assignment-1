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




if ($_POST && !empty($_POST['nameUser']) && !empty($_POST['username'])) {
    // inputs
    $nameUser = filter_input(INPUT_POST, 'nameUser', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);



    // salting and hashing password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);






    $userQuery = "INSERT INTO users (name, username, password, role) VALUES (:name, :username, :password, :role)";
    $userStatement = $db->prepare($userQuery);

    $userStatement->bindValue(':name', $nameUser);
    $userStatement->bindValue(':username', $username);
    $userStatement->bindValue(':password', $hashedPassword);
    $userStatement->bindValue(':role', $role);

    if($userStatement ->execute()) {
        echo "success";
        header("Location: index.php");
    }
    else{
        echo "failed";
    }


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

    <?php include 'header.php' ?>


    <main>
    

        <div>
            <div id="form-container">

                <form action="#" method="post">
                    <h1>Create an account</h1>
                    <label for="nameUser">Name</label>
                    <input type="text" id="nameUser" name="nameUser" > 
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" > 
                    <label for="password">Password</label>
                    <input type="text" id="password" name="password" > 
                    <input type="hidden" id="role" name="role" value="browser"> 
                
                
                <button type="submit">Sign Up</button>
                
                </form>
            </div>
        </div>
    </main>

    <?php include 'footer.php' ?>
    
</body>
</html>
