<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/
session_start();

require('connect.php');




if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // gather data.
    $nameUser = $_POST['nameUser'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $reenterPassword = $_POST['reenterPassword'];
    $role = $_POST['role'];

    $errors = [];

    // validate
    if(empty($nameUser)){
        $errors[] = "Invalid Name: Cannot be empty";
    }
    if (!preg_match("/^[a-zA-Z0-9]{3,20}$/", $username)) {
        $errors[] = "Invalid username. Must be 2-20 characters long and contain only letters and numbers (no spaces or special characters).";
    }
   
    if (strlen($password) < 3) {
        $errors[] = "Password must be at least 3 characters long.";
    }
    
    if ($password !== $reenterPassword) {
        $errors[] = "Passwords do not match.";
    }
   
    if ($role !== "browser") {
        $errors[] = "Invalid role. It must be 'browser'.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: invalidinput.php");
        exit;
    }
    else{
        
        // inputs
        $nameUser = filter_var($nameUser, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $username = filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $role = filter_var($role, FILTER_SANITIZE_FULL_SPECIAL_CHARS);



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

                    <label for="reenterPassword">Re-enter Password</label>
                    <input type="text" id="reenterPassword" name="reenterPassword" > 

                    <input type="hidden" id="role" name="role" value="browser"> 
                
                
                <button type="submit">Sign Up</button>
                
                </form>
            </div>
        </div>
    </main>

    <?php include 'footer.php' ?>
    
</body>
</html>
