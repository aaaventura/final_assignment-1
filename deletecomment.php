<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-04-10
    Description: no validation for delete comment

****************/

session_start();

require('connect.php');

// checks login credentials
require('validateadmin.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $commentid = $_GET['commentid'];

    if(!is_numeric($commentid)){
        $errors[] = "id must be a number";
    }
    if (!empty($errors)){
        $_SESSION['errors'] = $errors;
        header("Location: invalidinput.php");
        exit;
    }
    else{
        // deleting the comment
        $commentid = filter_var($commentid, FILTER_SANITIZE_NUMBER_INT);

        // Deletes from specific row based on id.
        $query = "DELETE FROM comments WHERE commentid = :commentid";
        $statement = $db->prepare($query);
        $statement->bindValue(':commentid', $commentid, PDO::PARAM_INT);
        $statement->execute();
    }
} 
else{
    $errors[] = "invalid server request";
    $_SESSION['errors'] = $errors;
    
    header("Location: invalidinput.php");
    exit;
}

// return
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>