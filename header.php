<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-01-10
    Description: header validated

****************/

require('sessiondisplay.php');

$role = $_SESSION['role'] ?? null;

if (isset($role)){
    $role = filter_var($_SESSION['role'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }


$librarypermissions = ['admin', 'artist', 'employee', 'browser'];
$artistpermissions = ['admin', 'artist'];

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<header>
    <div id="head-title">
        <h1 id="mainHeader"><a href="index.php">Audio Library Database</a></h1>
    </div>
    <section id="header-elements">
        <nav id="navigation">
            <ul>
                <li <?= ($currentPage == "index.php") ? 'id="nav-selected"' : ''; ?>><a href="index.php" >Home</a></li>
                <?php if(isset($_SESSION['role']) && in_array($role, $librarypermissions)): ?>
                    <li <?= ($currentPage == "audiolibrary.php") ? 'id="nav-selected"' : ''; ?>><a href="audiolibrary.php">Search Library</a></li> 
                <?php endif; ?>
                <?php if(isset($_SESSION['role']) && in_array($role, $artistpermissions)): ?>
                    <li <?= ($currentPage == "artistpage.php") ? 'id="nav-selected"' : ''; ?>><a href="artistpage.php">Artists Upload</a></li>
                <?php endif; ?>
                <?php if(isset($_SESSION['role']) && $role === 'admin'): ?>
                    <li <?= ($currentPage == "adminpage.php") ? 'id="nav-selected"' : ''; ?>><a href="adminpage.php">admin</a></li>
                <?php endif; ?>
            </ul> 
        </nav>
        <?php if(isset($_SESSION['role']) && $currentPage != 'audiolibrary.php'): ?>
            <form id="universal-search" action="audiolibrary.php" method="POST">
                <div>
                    <label for="search">Search Database</label>
                    <input type="text" id="search" name="search" required> <br>
                </div>
                <fieldset>
                    <input type="radio" name="searchBy" id="title-nav" value="title" required checked/> <label for="title-nav">Title</label><br />
                    <input type="radio" name="searchBy" id="artist-nav" value="artist" /> <label for="artist-nav">artist</label><br />
                    <input type="radio" name="searchBy" id="genre-nav" value="genre" /> <label for="genre-nav">Genre</label><br />
                </fieldset>
                <button type="submit" name="submit">Search</button>
            </form>
        <?php endif; ?>
    </section>
</header>





