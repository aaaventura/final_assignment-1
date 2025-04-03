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




// checks login credentials
$allowedRoles = ['admin', 'employee'];

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles)) {
    header("Location: accessdenied.php");
    exit;
}




// get id
$audioId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
// If $id is not an INT, return to index.php
if(!$audioId){
    //header("Location: index.php");
    echo "not correct value in get";
}

// fetching data from specific row.
$query = "SELECT * FROM audio WHERE id = :id";
$statement = $db -> prepare($query);
$statement -> bindValue( ':id', $audioId, PDO::PARAM_INT);
$statement->execute();
$audioData = $statement -> fetch(PDO::FETCH_ASSOC);

$fileLocation = $audioData['fileLocation'];


if (file_exists($fileLocation)) {
    // Set headers to force download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($fileLocation) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fileLocation));
    ob_clean();
    flush();
    readfile($fileLocation);
    exit;
} else {
    echo "File not found.";
}






?>

    
