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
    <div id="page-border">
        <?php include 'header.php' ?>

        <main>

            <h1>Audio Library</h1>


                <form id="search-form" method="POST">
                    <label for="search">Search Database</label>
                    <input type="text" id="search" name="search" required> 
                    <fieldset>
                        <input type="radio" name="searchBy" id="title" value="title" checked required/> <label for="title">Title</label>
                        <input type="radio" name="searchBy" id="artist" value="artist" /> <label for="artist">artist</label>
                        <input type="radio" name="searchBy" id="genre" value="genre" /> <label for="genre">Genre</label>
                    </fieldset>
                    
                    <button type="submit" name="submit">Search</button>
                </form>
               
            


            <div id="audio-container">

                <?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                    
                    <div id="link-section">
                        <a id="link-button" href="audiolibrary.php">Reset Search</a>
                    </div>

                <?php endif; ?>


                <?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>


                    <div id="search-elements">
                        <p class="black-text">Search: <?= isset($search) ? $search : "nothing" ?></p>
                        <p class="black-text">Search By: <?= isset($searchBy) ? $searchBy : "nothing" ?></p>
                    </div>
                    



                    <?php if(empty($audioFilesData)): ?>
                        <h1> No files found </h1>
                    <?php endif; ?>


                <?php endif; ?>

                <?php if(!empty($audioFilesData)): ?>

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

                                <?php if($_SESSION['role'] === "employee" || $_SESSION['role'] === "admin" ): ?>
                                    <a href="employeedownload.php?id=<?=$audioData['id']?>">DOWNLOAD</a>
                                <?php endif; ?>
                            </div>
                        </section>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>
            

        </main>


        <?php include 'footer.php' ?>
    </div>
</body>
</html>
