<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/
require('sessiondisplay.php');

$role = $_SESSION['role'] ?? null;

if (isset($role)){
    $role = filter_var($_SESSION['role'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    //echo "role: " . $role;
    }



$librarypermissions = ['admin', 'artist', 'employee', 'browser'];
$artistpermissions = ['admin', 'artist'];
?>




<header>
    <h1><a href="index.php">Audio Library Database</a></h1>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php if(isset($_SESSION['role']) && in_array($role, $librarypermissions)): ?>
                <li><a href="audiolibrary.php">Search Library</a></li> 
            <?php endif; ?>

            <?php if(isset($_SESSION['role']) && in_array($role, $artistpermissions)): ?>
                <li><a href="artistpage.php">Artists Upload</a></li>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['role']) && $role === 'admin'): ?>
                <li><a href="adminpage.php">admin</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>





