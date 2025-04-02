<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/
session_start();
require('connect.php');
echo "Logged in as: " . $_SESSION['name'];

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); 
    exit;
}






// get id
$audioId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
// If $id is not an INT, return to index.php
if(!$audioId){
    //header("Location: index.php");
    echo "not correct value in get";
}

// fetching data from specific row.
$query = "SELECT * FROM audio WHERE id = :id";
$statement = $db -> prepare($query);
$statement -> bindValue( ':id', $audioId, PDO::PARAM_INT);
$statement->execute();
$audioData = $statement -> fetch(PDO::FETCH_ASSOC);






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






// after post. either delete or edit.


// _POST all relevant data for processes.
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$artist = filter_input(INPUT_POST, 'artist', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$producer = filter_input(INPUT_POST, 'producer', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$creator = filter_input(INPUT_POST,'creator', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$genre = filter_input(INPUT_POST,'genre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$description = filter_input(INPUT_POST,'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$command = filter_input(INPUT_POST,'command', FILTER_SANITIZE_STRING);
// Checks for Update command.

//extra todo: make sure this doesn't activate when first time--only activate when a post command is given.
// it is making unknown show up after the "Logged in as: admin"
if($command === 'Update'){
    
    // Updates specific row based on id.
    $query     = "UPDATE audio SET artist = :artist, producer = :producer, creator = :creator, genre = :genre, description = :description WHERE id = :id";
    $statement = $db->prepare($query);

    $statement->bindValue(':artist', $artist);
    $statement->bindValue(':producer', $producer);
    $statement->bindValue(':creator', $creator);
    $statement->bindValue(':genre', $genre);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':id', $id);
   
    $statement->execute();

    // Return to index when complete.
    header("Location: adminpage.php");
}

// Elseif Delete command. 
elseif($command =='Delete'){

    // Deletes from specific row based on id.
    $query = "DELETE FROM audio WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();

    // Return to index when complete.
    header("Location: adminpage.php");
}

else{
    echo "unknown";
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
        <h1>Audio Library Database</h1>
        <nav>
            <ul>
                <li><a href="#">login</a></li>
                <li><a href="#">Search Library</a></li>
                <li><a href="#">Upload</a></li>
                <li><a href="edit.php">Edit</a></li>
            </ul>
        </nav>
    </header>

    <main>
    

        <div>
            <div id="form-container">
                <p>Edit audio file data</p>

                <audio controls>
                            <source src="<?=$audioData['fileLocation'] ?>" type="<?php fileExtension($audioData['fileLocation'])?>">
                            your browser does not support the audio element
                        </audio>
                <form action="editaudio.php" method="post">
                    <label for="artist">artist</label>
                    <input type="text" id="artist" name="artist" value="<?= htmlspecialchars_decode($audioData['artist']); ?>"> 
                    <label for="producer">producer</label>
                    <input type="text" id="producer" name="producer" value="<?= htmlspecialchars_decode($audioData['producer']); ?>"> 
                    <label for="creator">creator</label>
                    <input type="text" id="creator" name="creator" value="<?= htmlspecialchars_decode($audioData['creator']); ?>"> 
                    <label for="genre">genre</label>
                    <input type="text" id="genre" name="genre" value="<?= htmlspecialchars_decode($audioData['genre']); ?>"> 
                    <label for="description">description</label>
                    <textarea id="description" name="description" rows="10"><?= htmlspecialchars_decode($audioData['description']) ?></textarea>
                    <input type="hidden" name="id" value="<?= $audioData['id']?>">
                    <div>
                        <input type="submit" name="command" value="Update">
                        <input type="submit" name="command" value="Delete">
                    </div>
                </form>
            </div>
        </div>

        
        
    </main>

    <footer>
        <p>&copy; 2025 My CMS. All rights reserved.</p>
    </footer>
    
</body>
</html>

<!--
so right now, I have to think about a way to make users and the webpage too, this means, that I have to access... the database for users? 
-->