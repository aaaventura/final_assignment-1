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
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); 
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

    $userQuery = "INSERT INTO users (name, username, password, role) VALUES (:name, :username, :password, :role)";
    $userStatement = $db->prepare($userQuery);

    $userStatement->bindValue(':name', $nameUser);
    $userStatement->bindValue(':username', $username);
    $userStatement->bindValue(':password', $password);
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

    <header>
        <h1>Audio Library Database adminpage</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="audiolibrary.php">Search Library</a></li> 
                <li><a href="#">Upload</a></li>
                <li><a href="edit.php">Edit</a></li>
                <li><a href="logout.php">Log Out</a></li>
                <li><a href="adminpage.php">admin</a></li>
            </ul>
        </nav>
    </header>

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
                    <spane>Description</spane>
                </div>
                <?php foreach($audioFilesData as $audioData): ?>

                    <ul class="audioFileDatabase">
                        <audio controls>
                            <source src="<?=$audioData['fileLocation'] ?>" type="<?php fileExtension($audioData['fileLocation'])?>">
                            your browser does not support the audio element
                        </audio>
                        <li><?=$audioData['id'] ?></li>
                        <li><?=$audioData['title'] ?></li>
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

<!--
so right now, I have to think about a way to make users and the webpage too, this means, that I have to access... the database for users? 
in the admin page, you should be able to see all of the database. all users. 
create new users
delete users.
update users
read users.
but thatt goes both ways for both users and also the data and users... so I will have to divide this thing into two.



next thing I have to figure out is...what should I do next? should I make the database? or something? I
I think the next thing I should do is display users. I guess... y'know?

so 
i have creating #
i have reading the database. #
then I have editing--updating and also deleting. 
and it works perfectly.

now, I just have to do this for... the users. lol.
-->