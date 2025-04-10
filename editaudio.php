<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-04-10
    Description: no errors; edit audio complete

****************/

session_start();

require('connect.php');

// checks login credentials
require('validateadmin.php');

if($_SERVER["REQUEST_METHOD"] == "GET"){
    $audioId = $_GET['id'];
    $errors = [];

    if(!is_numeric($audioId)){
        $errors = "id must be a number";
    }
    if (!empty($errors)){
        $_SESSION['errors'] = $errors;
        header("Location: invalidinput.php");
        exit;
    }
    else{
        $audioId = filter_var($audioId, FILTER_SANITIZE_NUMBER_INT);

        // fetching data from specific row.
        $query = "SELECT * FROM audio WHERE id = :id";
        $statement = $db -> prepare($query);
        $statement -> bindValue( ':id', $audioId, PDO::PARAM_INT);
        $statement->execute();
        $audioData = $statement -> fetch(PDO::FETCH_ASSOC);
    }
}

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
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = $_POST['id'];
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $producer = $_POST['producer'];
    $creator = $_POST['creator'];
    $genre = $_POST['genre'];
    $description = $_POST['description']; 

    $command = $_POST['command'];
    $validCommands = ['Update', 'Delete'];

    $errors = [];

    if (empty($id) || !is_numeric($id)) {
        $errors[] = "id must be a number";
    }
    if (empty($title)) {
        $errors[] = "title cannot be empty";
    }
    if (empty($artist)) {
        $errors[] = "artist cannot be empty.";
    }
    if (empty($genre)) {
        $errors[] = "genre cannot be empty.";
    }
    if (empty($description)) {
        $errors[] = "description cannot be empty.";
    }
    if (!in_array($command, $validCommands)) {
        $errors[] = "command must be 'Update' or 'Delete'.";
    }
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;

        header("Location: invalidinput.php");
        exit;
    }
    else{ 
        // continue with regular logic
        // _POST all relevant data for processes.
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
        $artist = filter_var($artist, FILTER_SANITIZE_SPECIAL_CHARS);
        $producer = filter_var($producer, FILTER_SANITIZE_SPECIAL_CHARS);
        $creator = filter_var($creator, FILTER_SANITIZE_SPECIAL_CHARS);
        $genre = filter_var($genre, FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);

        $command = filter_var($command, FILTER_SANITIZE_SPECIAL_CHARS);

        // check for update
        if($command === 'Update'){
            // Updates specific row based on id.
            $query     = "UPDATE audio SET title = :title, artist = :artist, producer = :producer, creator = :creator, genre = :genre, description = :description WHERE id = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':title', $title);
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
            // retrieve file path
            $query = "SELECT fileLocation FROM audio WHERE id = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            $file = $statement->fetch(PDO::FETCH_ASSOC);
            //echo $file;

            if($file){
                    $filePath = $file['fileLocation'];
                    //echo $filePath;

                    if(file_exists($filePath)){
                        unlink($filePath);
                    }
            }

            // Deletes from specific row based on id.
            $query = "DELETE FROM audio WHERE id = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
        
            // deleting all comments corresponding to audio
            $query = "DELETE FROM comments WHERE audioid = :audioid";
            $statement = $db->prepare($query);
            $statement->bindValue(':audioid', $id, PDO::PARAM_INT);
            $statement->execute();

            // Return to index when complete.
            header("Location: adminpage.php");
        }
        else{
            echo "unknown";

            header("Location: adminpage.php");
        } 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Edit Audio</title>
</head>
<body>
<div id="page-border">
    <?php include 'header.php' ?>
    <main>
        <form id="form-upload" action="editaudio.php" method="post">
        <h1>Edit audio file data</h1>
            <audio controls>
                <source src="<?=$audioData['fileLocation'] ?>" type="<?= fileExtension($audioData['fileLocation'])?>">
                your browser does not support the audio element
            </audio>
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars_decode($audioData['title']); ?>" required> 
            <label for="artist">Artist</label>
            <input type="text" id="artist" name="artist" value="<?= htmlspecialchars_decode($audioData['artist']); ?>" required> 
            <label for="producer">Producer</label>
            <input type="text" id="producer" name="producer" value="<?= htmlspecialchars_decode($audioData['producer']); ?>"> 
            <label for="creator">Creator</label>
            <input type="text" id="creator" name="creator" value="<?= htmlspecialchars_decode($audioData['creator']); ?>"> 
            <label for="genre">Genre</label>
            <input type="text" id="genre" name="genre" value="<?= htmlspecialchars_decode($audioData['genre']); ?>" required> 
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="10" required><?= htmlspecialchars_decode($audioData['description']) ?></textarea>
            <input type="hidden" name="id" value="<?= $audioData['id']?>">
            <div>
                <input class="submit-button" type="submit" name="command" value="Update">
                <input class="submit-button" type="submit" name="command" value="Delete">
            </div>
        </form>
    </main>
    <?php include 'footer.php' ?>
    </div>
</body>
</html>

