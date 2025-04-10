<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/
session_start();

require('connect.php');



// checks login credentials
$allowedRoles = ['admin', 'artist'];
require('validaterole.php');
validateSessionRole($allowedRoles);

   

//upload
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    //var_dump($_FILES['audio']);
//upload new file

    if(isset($_FILES['audio']) && $_FILES['audio']['error'] == UPLOAD_ERR_OK ){
        
        $uploadDirectory = 'audioFiles/';

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }
        
        
        $fileName = basename($_FILES['audio']['name']);
        $filename = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $fileName);
        $fileName = time() . "_" . $fileName;

        $tempPath = $_FILES['audio']['tmp_name'];

        // allowed types
        $allowedMimeTypes = ['audio/mpeg', 'audio/wav', 'audio/x-wav', 'audio/ogg', 'audio/aac'];

        $fileMimeType = mime_content_type($tempPath);


        if (in_array($fileMimeType, $allowedMimeTypes)) {
            $errors = [];

            // file size limit
            if($_FILES['audio']['size'] > 20000000){
                $errors[] = "File size exceeds maximum: 20MB";
            }

            $destination = $uploadDirectory . $fileName;


            if (move_uploaded_file($tempPath, $destination)) {
                echo "The file " . htmlspecialchars($fileName) . " has been uploaded successfully.";

                $title = $_POST['title'];
                $artist = $_POST['artist'];
                $producer = isset($_POST['producer']) ? $_POST['producer'] : ''; 
                $creator = isset($_POST['creator']) ? $_POST['creator'] : ''; 
                $genre = $_POST['genre'];
                $description = $_POST['description']; 


                


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
                
                if (!empty($errors)) {
                    $_SESSION['errors'] = $errors;
                    header("Location: invalidinput.php");
                    exit;
                }
                else{
                    // continue regular logic

                    // saving to database metadata
                    $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
                    $artist = filter_var($artist, FILTER_SANITIZE_SPECIAL_CHARS);
                    $producer = isset($_POST['producer']) ? filter_var($producer, FILTER_SANITIZE_SPECIAL_CHARS): ''; 
                    $creator = isset($_POST['creator']) ? filter_var($creator, FILTER_SANITIZE_SPECIAL_CHARS) : ''; 
                    $genre = filter_var($genre, FILTER_SANITIZE_SPECIAL_CHARS);
                    $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);


                    $query = "INSERT INTO audio (fileLocation, title, artist, producer, creator, genre, description) VALUES (:fileLocation, :title, :artist, :producer, :creator, :genre, :description)";

                    $statement = $db->prepare($query);

                //bind values
                    $statement -> bindValue(':fileLocation', $destination);
                    $statement -> bindValue(':title', $title);
                    $statement -> bindValue(':artist', $artist);
                    $statement -> bindValue(':producer', $producer);
                    $statement -> bindValue(':creator', $creator);
                    $statement -> bindValue(':genre', $genre);
                    $statement -> bindValue(':description', $description);

                    //execute
                    if($statement -> execute()){
                        echo "upload to database success";
                        header("Location: audiolibrary.php");
                    }
                }
            } 
            else {
                echo "Error: File could not be saved.";
            }
        } 
        else {
            echo "Error: Invalid file type. Only MP3, WAV, OGG, and AAC files are allowed.";
        }
    } 
    else {
        echo "Error: No file uploaded or another upload error happened.";
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
    <title>Homepage</title>
</head>
<body>
<div id="page-border">
    <?php include 'header.php' ?>


    <main>
   

        
            
            <form id="form-upload" action="#" method="post" enctype="multipart/form-data">

                <h2>Upload Your File</h2>

                <label for="audio">Choose a file to upload:</label>
                <input type="file" name="audio" id="audio" accept="audio/*" required>

                <label for="title">Title</label>
                <input type="text" name="title" id="title" placeholder="title" required>

                <label for="artist">Artist</label>
                <input type="text" name="artist" id="artist" placeholder="artist" value="<?= $_SESSION['name'] ?>">

                <label for="producer">Producer</label>
                <input type="text" name="producer" id="producer" placeholder="producer">

                <label for="creator">Creator</label>
                <input type="text" name="creator" id="creator" placeholder="creator">

                <label for="genre">Genre</label>
                <input type="text" name="genre" id="genre" placeholder="genre" required>

                <label for="description">Description</label>
                <textarea id="description" name="description" rows="10" placeholder="description" required></textarea>
                

                <button type="submit">Upload</button>
            </form>

        
            
        
        

    </main>

    <?php include 'footer.php' ?>
    </div>
</body>
</html>

