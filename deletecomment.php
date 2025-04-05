<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/
session_start();
require('connect.php');



// checks login credentials
require('validateadmin.php');



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