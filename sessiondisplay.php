<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-04-10
    Description: no validation for session display

****************/

if (isset($_SESSION['name'])){

    $name = $_SESSION['name'];
    //$role = $_SESSION['role'];

    if(!is_string($name) || empty($name)){
        echo "Invalid session";
        header("Location: accessdenied.php");
        exit;
    }
    
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    echo "Logged in as: " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
} 
else{
    echo "Not logged in.";
}

?>