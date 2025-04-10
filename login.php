<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-04-10
    Description: login validation complete

****************/

session_start();

require('connect.php');

//before sanitization, validation.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //validating
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captcha = $_POST['textBox'];

    $captchasession = $_SESSION['captcha'];
   
    $errors = [];

    if(!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)){
        $errors[] = "Invalid username. Must be 2-20 characters long and contain only letters and numbers (no spaces or special characters).";
    }
    if(strlen($password) < 3){
        $errors[] = "Password must be at least 3 characters long.";
    }
    if(empty($captcha) || $captcha != $captchasession){
        $errors[] = "Captcha filled out incorrectly.";
    }
    if (!empty($errors)){
        $_SESSION['errors'] = $errors;

        header("Location: invalidinput.php");
        exit;
    }
    else{
            // sanitizing
            $username = filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Prepare SQL query
            $query = "SELECT * FROM users WHERE username = :username";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if ($user && password_verify($password, $user['password'])) {
                // store users in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; 

                header("Location: index.php"); 
                exit;
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
    <title>Login Denied</title>
</head>
<body>
<div id="page-border">
    <?php include 'header.php' ?>
    <main>
        <h1> Login Denied.</h1>
        <p class="black-text"> Invalid Login Credentials</p>
        <div id="link-section">
            <a id="link-button" href="index.php">Return Home</a>
        </div>
    </main>
    <?php include 'footer.php' ?>
    </div>
</body>
</html>



