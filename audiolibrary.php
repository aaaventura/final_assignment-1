<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/
session_start();

require('connect.php');



// checks login credentials
$allowedRoles = ['admin', 'artist', 'employee', 'browser'];
require('validaterole.php');
validateSessionRole($allowedRoles);


//echo in_array($_SESSION['role'], $adminemployee);







// diaply audio database. 
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

//search database. 

$displayQuery = "SELECT * FROM audio order by id DESC";

$displayStatement = $db -> prepare($displayQuery);

$displayStatement -> execute();

$audioFilesData = $displayStatement -> fetchAll(PDO::FETCH_ASSOC);




if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $search = $_POST['search'];
    $searchBy = $_POST['searchBy'];

    $validSearchBy = ['title', 'artist', 'genre'];

    $errors = [];
    $regPattern = "/^[a-zA-Z0-9\s]+$/";

    if (empty($search) || !preg_match($regPattern, $search)) {
        $errors[] = "search cannot be empty or include special characters.";
    }
    if (!in_array($searchBy, $validSearchBy)) {
        $errors[] = "searchBy must be valid value (title, artist, genre)";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: invalidinput.php");
        exit;
    }
    else{
        // continue regular logic.

        $search = filter_var($search, FILTER_SANITIZE_STRING);
        $searchBy = filter_var($searchBy, FILTER_SANITIZE_STRING);
        
        //echo "search triggered";
        
        $query = "SELECT * FROM audio WHERE $searchBy LIKE :search";

        $statement = $db -> prepare($query);
        
        
        $statement -> bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        //$statement -> debugDumpParams();
        $statement -> execute();

        $audioFilesData = $statement -> fetchAll(PDO::FETCH_ASSOC);


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
            <p>Audio Library</p>
            <form method="POST">
            <label for="search">Search Database</label>
            <input type="text" id="search" name="search" required> <br>
            <input type="radio" name="searchBy" id="title" value="title" checked/> <label for="title">Title</label><br />
            <input type="radio" name="searchBy" id="artist" value="artist" /> <label for="artist">artist</label><br />
            <input type="radio" name="searchBy" id="genre" value="genre" /> <label for="genre">Genre</label><br />
            <input type="submit" name="submit">
            </form>
            <?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                <p><a href="audiolibrary.php">Reset Search</a></p>
            <?php endif; ?>
        </div>


        <div>
        
            <!-- this is where I'll put a php loop or something liek that, y'know?-->

            <?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                    <p>Search: <?= isset($search) ? $search : "nothing" ?></p>
                    <p>search By: <?= isset($searchBy) ? $searchBy : "nothing"?></p>



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
                        <?php if($_SESSION['role'] === "employee" || $_SESSION['role'] === "admin" ): ?>
                            <span>Actions</span>
                        <?php endif; ?>
                        

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

                            <?php if($_SESSION['role'] === "employee" || $_SESSION['role'] === "admin" ):  ?>
                            <li><a href="employeedownload.php?id=<?=$audioData['id']?>">DOWNLOAD</a></li>
                            <?php endif; ?>


                        </ul>
                    <?php endforeach; ?>

                <?php endif; ?>


                    
            <?php endif; ?>

            
        </div>
        

    </main>
    <?php include 'footer.php' ?>
</body>
</html>
