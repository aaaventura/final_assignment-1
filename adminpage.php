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


// inputs
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
                        header("Location: adminpage.php");
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






    // create new user
    if (!empty($_POST['nameUser']) && !empty($_POST['username'])) {


        // gather data.
        $nameUser = $_POST['nameUser'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $errors = [];

        $allowedRoles = ['admin', 'artist', 'employee', 'browser'];

        // validate
        if(empty($nameUser)){
            $errors[] = "Invalid Name: Cannot be empty";
        }
        if (!preg_match("/^[a-zA-Z0-9]{3,20}$/", $username)) {
            $errors[] = "Invalid username. Must be 2-20 characters long and contain only letters and numbers (no spaces or special characters).";
        }
       
        if (strlen($password) < 3) {
            $errors[] = "Password must be at least 3 characters long.";
        }
        
       
        if (!in_array($role, $allowedRoles)) {
            $errors[] = "Invalid role. It must be a valid role. (Admin, Employee, Artist, Browser)";
        }
    
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: invalidinput.php");
            exit;
        }

        // inputs
        $nameUser = filter_var($nameUser, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $username = filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $role = filter_var($role, FILTER_SANITIZE_FULL_SPECIAL_CHARS);


    
    
    
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


        <div id="admin-create">

            <form id="admin-form" action="#" method="post">
        
                <h2>Create New User</h2>

                <input type="text" id="nameUser" name="nameUser" placeholder="name" required> 
                <input type="text" id="username" name="username" placeholder="username" required> 
                <input type="text" id="password" name="password" placeholder="password" required> 
                <select id="role" name="role">
                    <option value="admin">Admin</option>
                    <option value="employee">Employee</option>
                    <option value="artist">Artist</option>
                    <option value="browser">Browser</option>
                </select>

                <button type="submit">Create</button>
            </form>

            
            <form id="admin-form" action="#" method="post" enctype="multipart/form-data">
                <h2>Create New Audio File</h2>
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
        </div>
   



        <section id="admin-database">
            <div id="users-database" class="scroll">
                <h2>user database</h2>


                <?php if(empty($usersData)): ?>
                    <section class="audio-container">
                        <h1> No Users Found? </h1>
                    </section>
                

                <?php else: ?> 

                    
                    <?php foreach($usersData as $user): ?>

                        <section class="audio-container">
                            
                            <div>
                        
                                <h1>Name</h1>
                                <p><?= htmlspecialchars_decode($user['name']); ?> </p> 
                                <h1>Username</h1>
                                <p><?= htmlspecialchars_decode($user['username']); ?></p> 
                                <h1 >Password</h1>
                                <p><?= htmlspecialchars_decode($user['password']); ?></p> 
                                <h1 >Role</h1>
                                <p><?= htmlspecialchars_decode($user['role']); ?></p> 
                                
                        
                                <a href="edituser.php?id=<?=$user['id']?>">EDIT</a>
                                
                            </div>
                        </section>

                
                    <?php endforeach; ?>

                <?php endif; ?>
            </div>


            <div id="audio-database" class="scroll">
                <h2>audio database</h2>
                
                <?php if(empty($audioFilesData)): ?>
                    <section class="audio-container">
                        <h1> No files found </h1>
                    </section>
                    


                <?php else: ?> 
                    <?php foreach($audioFilesData as $audioData): ?>


                        <section class="audio-container">
                            <audio controls>
                                        <source src="<?=$audioData['fileLocation'] ?>" type="<?php fileExtension($audioData['fileLocation'])?>">
                                        your browser does not support the audio element
                                    </audio>
                            <div>
                                <h1>Title</h1>
                                
                                <p><a href="audiopage.php?id=<?=$audioData['id']?>"><?= htmlspecialchars_decode($audioData['title']); ?> </a></p> 
                                <h1>Artist</h1>
                                <p><?= htmlspecialchars_decode($audioData['artist']); ?> </p> 
                                <h1>Producer</h1>
                                <p><?= htmlspecialchars_decode($audioData['producer']); ?></p> 
                                <h1 >Creator</h1>
                                <p><?= htmlspecialchars_decode($audioData['creator']); ?></p> 
                                <h1 >Genre</h1>
                                <p><?= htmlspecialchars_decode($audioData['genre']); ?></p> 
                                <h1 >Description</h1>
                                <p><?= htmlspecialchars_decode($audioData['description']) ?></p>

                            
                                <a href="editaudio.php?id=<?=$audioData['id']?>">EDIT</a>
                                
                            </div>
                        </section>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>
        
        </section>






        <div>
            
            
        </div>
        

    </main>

    <?php include 'footer.php' ?>
    </div>
</body>
</html>

