<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-04-10
    Description: no validation for logout

****************/

    session_start();
    session_destroy();
    
    header("Location: index.php");
    exit;
?>