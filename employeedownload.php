<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-04-10
    Description: nothing to validate in employee download

****************/

session_start();

require('connect.php');

// checks login credentials
$allowedRoles = ['admin', 'employee'];

require('validaterole.php');
validateSessionRole($allowedRoles);

// get id
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $audioId = $_GET['id'];
    $errors = [];

    if (!is_numeric($audioId)){
        $errors[] = "id must be a number";
    }
    if (!empty($errors)){
        $_SESSION['errors'] = $errors;

        header("Location: invalidinput.php");
        exit;
    }
    else{
        $audioId = filter_var($audioId, FILTER_SANITIZE_NUMBER_INT);

        // fetching data from specific row.
        $query = "SELECT * FROM audio WHERE id = :id";
        $statement = $db -> prepare($query);
        $statement -> bindValue( ':id', $audioId, PDO::PARAM_INT);
        $statement->execute();
        $audioData = $statement -> fetch(PDO::FETCH_ASSOC);

        $fileLocation = $audioData['fileLocation'];

        if (file_exists($fileLocation)){
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
        } 
        else{
            $errors[] = "No file Found";
            $_SESSION['errors'] = $errors;

            header("Location: invalidinput.php");
        }
    }
}
?>

    
