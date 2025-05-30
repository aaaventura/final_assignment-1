<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-04-10
    Description: no validation for post comment

****************/

session_start();

require('connect.php');

// verifying user
$allowedRoles = ['admin', 'artist', 'employee', 'browser'];

require('validaterole.php');
validateSessionRole($allowedRoles);

// for comment section
// Checks if title and post are empty.
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // recieving input
    $audioid = $_POST['audioid'];
    $username = $_SESSION['username'];
    $comment = $_POST['comment'];
    $currentTimestamp = date('Y-m-d H:i:s');
    $captcha = $_POST['textBox'];

    $captchasession = $_SESSION['captcha'];

    $errors = [];
    
    // validation
    if(!is_numeric($audioid)){
        $errors[] = "audioid must be a number";
    }
    if (!preg_match("/^[a-zA-Z0-9]{2,20}$/", $username)){
        $errors[] = "Invalid username. Must be 2-20 characters long and contain only letters and numbers (no spaces or special characters).";
    }
    if(empty($comment)){
        $errors[] = "Invalid Comment. Must not be empty.";
    }
    if(empty($captcha) || $captcha != $captchasession){
        $errors[] = "Captcha filled out incorrectly.";

        // store comment input in session
        $_SESSION['comment'] = $comment;

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
    if (!empty($errors)){
        $_SESSION['errors'] = $errors;

        header("Location: invalidinput.php");
        exit;
    }
    // no errors, continue with logic.
    else{
        $audioid = filter_var($audioid, FILTER_SANITIZE_NUMBER_INT);
        $username = filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $comment = filter_var($comment, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
        $query = "INSERT INTO comments (audioid, username, comment, timestamp) VALUES (:audioid, :username, :comment, :timestamp)";
        $statement = $db->prepare($query);
    
        $statement->bindValue(':audioid', $audioid);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':comment', $comment);
        $statement->bindValue(':timestamp', $currentTimestamp);
        
        if($statement ->execute()){
            echo "success";
            //$statement -> debugDumpParams();
            echo $audioid;
            header('Location: audiopage.php?id=' . $audioid);
        }
    }
}
?>