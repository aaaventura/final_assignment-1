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


// verifying user
$allowedRoles = ['admin', 'artist', 'employee', 'browser'];

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles)) {
    header("Location: accessdenied.php");
    exit;
}







//for comment section

//filling a comment

// Checks if title and post are empty.
if ($_POST && !empty($_POST['comment'])) {

    $audioid = filter_input(INPUT_POST, 'audioid', FILTER_SANITIZE_NUMBER_INT);


    $username = $_SESSION['username'];

    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    

    $currentTimestamp = date('Y-m-d H:i:s');

  
    $query = "INSERT INTO comments (audioid, username, comment, timestamp) VALUES (:audioid, :username, :comment, :timestamp)";
    $statement = $db->prepare($query);

 
    $statement->bindValue(':audioid', $audioid);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':comment', $comment);
    $statement->bindValue(':timestamp', $currentTimestamp);
    
    
    if($statement ->execute()) {
        echo "success";
        //$statement -> debugDumpParams();
        echo $audioid;
        header('Location: audiopage.php?id=' . $audioid);
    }
}




function timedateformat($date) {
    // Format the date to "mm, dd, yyyy, hh:ii am/pm"
    $formattedDate = date('F d, Y, h:i a', strtotime($date));
    return $formattedDate;
}

?>