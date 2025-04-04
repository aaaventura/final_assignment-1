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


// verifying user
$allowedRoles = ['admin', 'artist', 'employee'];

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles)) {
    header("Location: accessdenied.php");
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




// printing comments for get
// SQL is written as a String.
$query = "SELECT * FROM comments WHERE audioid = :audioid ORDER BY timestamp DESC";
// A PDO::Statement is prepared from the query.
$statement = $db->prepare($query);

$statement -> bindValue(':audioid', $audioId);

// Execution on the DB server is delayed until we execute().
$statement->execute();

// Fetch all rows from the query result.
$commentposts = $statement->fetchAll(PDO::FETCH_ASSOC);




function timedateformat($date) {
    // Format the date to "mm, dd, yyyy, hh:ii am/pm"
    $formattedDate = date('F d, Y, h:i a', strtotime($date));
    return $formattedDate;
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
            <div id="form-container">
                <audio controls>
                            <source src="<?=$audioData['fileLocation'] ?>" type="<?php fileExtension($audioData['fileLocation'])?>">
                            your browser does not support the audio element
                        </audio>
                <form action="editaudio.php" method="post">
                    <h1 >title</h1>
                    <p><?= htmlspecialchars_decode($audioData['title']); ?> </p> 
                    <h1>artist</h1>
                    <p><?= htmlspecialchars_decode($audioData['artist']); ?> </p> 
                    <h1>producer</h1>
                    <p><?= htmlspecialchars_decode($audioData['producer']); ?></p> 
                    <h1 >creator</h1>
                    <p><?= htmlspecialchars_decode($audioData['creator']); ?></p> 
                    <h1 >genre</h1>
                    <p><?= htmlspecialchars_decode($audioData['genre']); ?></p> 
                    <h1 >description</h1>
                    <p><?= htmlspecialchars_decode($audioData['description']) ?></p>
                    
                </form>
            </div>
        </div>


        <div id="comments-section">

            <form action="postcomment.php" method="post">
                <!--  this is where the commens are goign to be handled. ez pz.-->
        
                <p>User: <?= $_SESSION['username']?></p>
                <input type="hidden" name="audioid" value="<?= $audioData['id'] ?>">
    
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" rows="4" cols="50" required></textarea>
                
                <input type="submit" value="Submit">
            </form>
        </div>

        <div id="comments">

        <?php if(empty($commentposts)): ?> 
            <h1>no comments yet.</h1>

        <?php else: ?>
            <?php foreach($commentposts as $comment): ?>


                <p><strong>Username:</strong> <?= $comment['username'] ?></p>
                <p><strong>Comment:</strong> <?= $comment['comment'] ?></p>
                <p><strong>Date:</strong> <?= $comment['timestamp'] ?></p>


                <?php if($_SESSION['role'] === 'admin'): ?>

                <?php endif; ?>
            
            <!-- Repeat for each comment -->
            <?php endforeach; ?>

        <?php endif; ?>
        </div>

        
        
    </main>

    <footer>
        <p>&copy; 2025 My CMS. All rights reserved.</p>
    </footer>
    
</body>
</html>
