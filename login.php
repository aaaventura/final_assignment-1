<?php
session_start();
require('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    //var_dump($user);

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        // store users in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; 

        header("Location: index.php"); 
        exit;
    } else {
        //echo "<p>Invalid login credentials. Please try again.</p>";
        
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
        <h1> Acess Denied.</h1>
        <p> Invalid Login Credentials</p>
        <p> Please return to the homepage: <a href="index.php">Here</a></p>

    </main>

    <?php include 'footer.php' ?>
    
</body>
</html>



