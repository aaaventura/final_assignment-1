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
$allowedRoles = ['admin'];

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles)) {
    header("Location: accessdenied.php");
    exit;
}



// deleting the comment
$commentid = filter_input(INPUT_GET, 'commentid', FILTER_SANITIZE_NUMBER_INT);

// Deletes from specific row based on id.
$query = "DELETE FROM comments WHERE commentid = :commentid";
$statement = $db->prepare($query);
$statement->bindValue(':commentid', $commentid, PDO::PARAM_INT);
$statement->execute();

// Return to index when complete.
header('Location: ' . $_SERVER['HTTP_REFERER']);

?>