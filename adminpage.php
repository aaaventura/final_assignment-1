<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/
session_start();

require('connect.php');


// checks login credentials
require('validateadmin.php');


//upload
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    //var_dump($_FILES['audio']);

    if(isset($_FILES['audio']) && $_FILES['audio']['error'] == UPLOAD_ERR_OK ){
        $uploadDirectory = 'audioFiles/';

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }
        
        
        $fileName = basename($_FILES['audio']['name']);
        $filename = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $fileName);

        $tempPath = $_FILES['audio']['tmp_name'];


        // allowed types
        $allowedMimeTypes = ['audio/mpeg', 'audio/wav', 'audio/x-wav', 'audio/ogg', 'audio/aac'];

        $fileMimeType = mime_content_type($tempPath);




        if (in_array($fileMimeType, $allowedMimeTypes)) {

            // file size limit
            if($_FILES['audio']['size'] > 10000000){
                echo "File size ezeeds limit: 10MB";
            }

            $destination = $uploadDirectory . $fileName;
            if (move_uploaded_file($tempPath, $destination)) {
                echo "The file " . htmlspecialchars($fileName) . " has been uploaded successfully.";

                // saving to database metadata
                $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
                $artist = filter_input(INPUT_POST, 'artist', FILTER_SANITIZE_SPECIAL_CHARS);
                $producer = isset($_POST['producer']) ? filter_input(INPUT_POST, 'producer', FILTER_SANITIZE_SPECIAL_CHARS): ''; 
                $creator = isset($_POST['creator']) ? filter_input(INPUT_POST, 'creator', FILTER_SANITIZE_SPECIAL_CHARS) : ''; 
                $genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_SPECIAL_CHARS);;
                $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);;

                // Display for debugging
                //var_dump($fileName, $artist, $producer, $creator, $genre, $description, $destination);


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
                    header("Location: adminpage.php");
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





// display user database
$displayUsersQuery = "SELECT * FROM users order by id DESC";

$displayUsersStatement = $db -> prepare($displayUsersQuery);

$displayUsersStatement -> execute();

$usersData = $displayUsersStatement -> fetchAll(PDO::FETCH_ASSOC);




//creating new users


if ($_POST && !empty($_POST['nameUser']) && !empty($_POST['username'])) {
    // inputs
    $nameUser = filter_input(INPUT_POST, 'nameUser', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);



    // salting and hashing password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);


    $userQuery = "INSERT INTO users (name, username, password, role) VALUES (:name, :username, :password, :role)";
    $userStatement = $db->prepare($userQuery);

    $userStatement->bindValue(':name', $nameUser);
    $userStatement->bindValue(':username', $username);
    $userStatement->bindValue(':password', $hashedPassword);
    $userStatement->bindValue(':role', $role);

    if($userStatement ->execute()) {
        echo "success";
        header("Location: adminpage.php");
    }
    else{
        echo "failed";
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
            <p>This is the starting page for my content management system.</p>
            <a href="#">Get Started</a>
        </div>


        <div>
            <h2>user database</h2>
            
                
            <form action="#" method="post">
                <label for="name">Create new user</label>
                <input type="text" name="nameUser" placeholder="name" required>
                <input type="text" name="username" placeholder="username" required>
                <input type="text" name="password" placeholder="password" required>
                <select id="role" name="role">
                    <option value="admin">Admin</option>
                    <option value="employee">Employee</option>
                    <option value="artist">Artist</option>
                    <option value="browser">Browser</option>
                </select>
                <button type="submit">Create</button>
            </form>

            <?php if(empty($usersData)): ?>
                <h1> No files found </h1>


            <?php else: ?> 

                <div class="audioFileDatabaseHeader">
                    <span>ID</span>
                    <span>Name</span>
                    <span>Username</span>
                    <span>Password</span>
                    <span>Role</span>
                    <span>Actions</span>
                </div>
                <?php foreach($usersData as $user): ?>

                    <ul class="audioFileDatabase">
                        
                        <li><?=$user['id'] ?></li>
                        <li><?=$user['name'] ?></li>
                        <li><?=$user['username'] ?></li>
                        <li><?=$user['password'] ?></li>
                        <li><?=$user['role'] ?></li>

                        <li><a href="edituser.php?id=<?=$user['id']?>">Edit</a></li>
               

                    </ul>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>

        <div>
            <h2>audio database</h2>
            <form action="#" method="post" enctype="multipart/form-data">
                <label for="audio">Choose a file to upload:</label>
                <input type="file" name="audio" id="audio" accept="audio/*" required>
                <input type="text" name="title" placeholder="title" required>
                <input type="text" name="artist" placeholder="artist" required>
                <input type="text" name="producer" placeholder="producer">
                <input type="text" name="creator" placeholder="creator">
                <input type="text" name="genre" placeholder="genre" required>
                <input type="text" name="description" placeholder="description" required>
                <button type="submit">Upload</button>
            </form>
            <!-- this is where I'll put a php loop or something liek that, y'know?-->
            <?php if(empty($audioFilesData)): ?>
                <h1> No files found </h1>


            <?php else: ?> 

                <div class="audioFileDatabaseHeader">
                    <span>Audio</span>
                    <span>ID</span>
                    <span>Title</span>
                    <span>Artist</span>
                    <span>Producer</span>
                    <span>Creator</span>
                    <span>Genre</span>
                    <span>Description</span>
                    <span>Action</span>
                </div>
                <?php foreach($audioFilesData as $audioData): ?>

                    <ul class="audioFileDatabase">
                        <audio controls>
                            <source src="<?=$audioData['fileLocation'] ?>" type="<?php fileExtension($audioData['fileLocation'])?>">
                            your browser does not support the audio element
                        </audio>
                        <li><?=$audioData['id'] ?></li>
                        <li><a href="audiopage.php?id=<?=$audioData['id']?>"><?=$audioData['title'] ?></a></li>
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

    <?php include 'footer.php' ?>
</body>
</html>

