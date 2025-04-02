<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/
session_start();

require('connect.php');

echo "Logged in as: " . $_SESSION['name'];


// checks login credentials
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); 
    exit;
}









// diaply audio database. 

$displayQuery = "SELECT * FROM audio order by id DESC";

$displayStatement = $db -> prepare($displayQuery);

$displayStatement -> execute();

$audioFilesData = $displayStatement -> fetchAll(PDO::FETCH_ASSOC);


// finding path data.
function fileExtension($file){

    $mimeTypes = [
        'mp3' => 'audio/mpeg',
        'wav' => 'audio/wav',
        'ogg' => 'audio/ogg',
        'aac' => 'audio/aac'
    ];

    $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

    $audioType = $mimeTypes[$fileExtension] ?? 'audio/mpeg';


    return $audioType;

}





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Homepage</title>
</head>
<body>

    <header>
        <h1>Audio Library Database adminpage</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#">login</a></li>
                <li><a href="#">Search Library</a></li>
                <li><a href="#">Upload</a></li>
                <li><a href="edit.php">Edit</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </nav>
    </header>

    <main>
   
        <div>
            <p>Audio Library</p>
            
        </div>


        <div>
        
            <!-- this is where I'll put a php loop or something liek that, y'know?-->
            <?php if(empty($audioFilesData)): ?>
                <h1> No files found </h1>


            <?php else: ?> 
                <?php foreach($audioFilesData as $audioData): ?>

                    <ul class="audioFileDatabase">
                        <h1>item <?=$audioData['id'] ?></h1>
                        <audio controls>
                            <source src="<?=$audioData['fileLocation'] ?>" type="<?php fileExtension($audioData['fileLocation'])?>">
                            your browser does not support the audio element
                        </audio>
                        <li><?=$audioData['id'] ?></li>
                        <li><?=$audioData['artist'] ?></li>
                        <li><?=$audioData['producer'] ?></li>
                        <li><?=$audioData['creator'] ?></li>
                        <li><?=$audioData['genre'] ?></li>
                        <li><?=$audioData['description'] ?></li>
                        <li><a href="editaudio.php?id=<?=$audioData['id']?>">Edit</a></li>

                    </ul>
                <?php endforeach; ?>

            <?php endif; ?>
            
        </div>
        

    </main>

    <footer>
        <p>&copy; 2025 My CMS. All rights reserved.</p>
    </footer>
    
</body>
</html>
