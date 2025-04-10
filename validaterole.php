<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-04-10
    Description: no validation for validate role

****************/

function validateSessionRole($allowedRoles){
    $role = $_SESSION['role'];
    
    if (isset($role)){
        $role = filter_var($role, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if(!in_array($role, $allowedRoles)){
            header("Location: accessdenied.php");
            exit;
        }
    }
    else{
        header("Location: accessdenied.php");
        exit;
    }
}
?>