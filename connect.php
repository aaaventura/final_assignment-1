<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-04-10
    Description: no validation done for connect

****************/

     define('DB_DSN','mysql:host=localhost;dbname=serverside;charset=utf8');
     define('DB_USER','serveruser');
     define('DB_PASS','gorgonzola7!');     
     
     try {
         
         $db = new PDO(DB_DSN, DB_USER, DB_PASS);
         
     } catch (PDOException $e) {
         print "Error: " . $e->getMessage();
         die(); 
     }
 ?>