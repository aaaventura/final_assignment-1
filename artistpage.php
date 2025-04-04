<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/
session_start();

require('connect.php');

if (isset($_SESSION['name'])) {
    echo "Logged in as: " . $_SESSION['name'];
} else {
    echo "Not logged in.";
}


// checks login credentials
$allowedRoles = ['admin', 'artist', 'employee'];

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles)) {
    header("Location: accessdenied.php");
    exit;
}









//upload
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    //var_dump($_FILES['audio']);

    if(isset($_FILES['audio']) && $_FILES['audio']['error'] == UPLOAD_ERR_OK ){
        $uploadDirectory = 'audioFiles/';

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }
        
        $fileName = basename($_FILES['audio']['name']);
        $tempPath = $_FILES['audio']['tmp_name'];

        $allowedMimeTypes = ['audio/mpeg', 'audio/wav', 'audio/x-wav', 'audio/ogg', 'audio/aac'];

        $fileMimeType = mime_content_type($tempPath);

        if (in_array($fileMimeType, $allowedMimeTypes)) {
            $destination = $uploadDirectory . $fileName;
            if (move_uploaded_file($tempPath, $destination)) {
                echo "The file " . htmlspecialchars($fileName) . " has been uploaded successfully.";

                // saving to database metadata
                $title = $_POST['title'];
                $artist = $_POST['artist'];
                $producer = isset($_POST['producer']) ? $_POST['producer'] : ''; // Optional field
                $creator = isset($_POST['creator']) ? $_POST['creator'] : ''; // Optional field
                $genre = $_POST['genre'];
                $description = $_POST['description'];

                // Display for debugging
                var_dump($fileName, $artist, $producer, $creator, $genre, $description, $destination);


                ///this is where i'm going to put it into the database 
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
            } else {
                echo "Error: File could not be saved.";
            }
        } else {
            echo "Error: Invalid file type. Only MP3, WAV, OGG, and AAC files are allowed.";
        }
    } else {
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

    <?php include 'header.php' ?>


    <main>
   

        <div>
            <h2>Upload Your File</h2>
            <form action="#" method="post" enctype="multipart/form-data">
                <label for="audio">Choose a file to upload:</label>
                <input type="file" name="audio" id="audio" accept="audio/*" required>
                <input type="text" name="title" placeholder="title" required>
                <input type="text" name="artist" placeholder="artist" value="<?= $_SESSION['name'] ?>">
                <input type="text" name="producer" placeholder="producer">
                <input type="text" name="creator" placeholder="creator">
                <input type="text" name="genre" placeholder="genre" required>
                <input type="text" name="description" placeholder="description" required>
                <button type="submit">Upload</button>
            </form>
            <!-- this is where I'll put a php loop or something liek that, y'know?-->
        
            
        </div>
        

    </main>

    <?php include 'footer.php' ?>
    
</body>
</html>

