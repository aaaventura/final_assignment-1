<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['captcha'])) {
    $_SESSION['captcha'] = $_POST['captcha'];
}
?>