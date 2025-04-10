<?php 

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-04-10
    Description: no validation for validate admin

****************/

$role = $_SESSION['role'];

if (isset($role)){
    $role = filter_var($role, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!is_string($role) || empty($role) || $role !== 'admin'){
        header("Location: accessdenied.php");
        exit;
    }
}
else{
    header("Location: accessdenied.php");
    exit;
}
?>