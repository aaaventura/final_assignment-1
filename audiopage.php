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
$allowedRoles = ['admin', 'artist'];

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





//for comment section

//filling a comment

// Checks if title and post are empty.
if ($_POST && !empty($_POST['comment'])) {

    $audioid = filter_input(INPUT_POST, 'audioid', FILTER_SANITIZE_NUMBER_INT);


    $username = $_SESSION['username'];
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // Capturing current time.
    $currentTimestamp = date('Y-m-d H:i:s');

    //  Build the parameterized SQL query and bind to the above sanitized values.
    $query = "INSERT INTO comments (audioid, username, comment, timestamp) VALUES (:audioid, :username, :comment, :timestamp)";
    $statement = $db->prepare($query);

    //  Bind values to the parameters
    $statement->bindValue(':audioid', $audioid);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':comment', $comment);
    $statement->bindValue(':timestamp', $currentTimestamp);
    
    //  Execute the INSERT.
    //  execute() will check for possible SQL injection and remove if necessary
    if($statement ->execute()) {
        echo "success";
    }
}



// displaying all comments

//so lets just think about this. 
//commentsid is the unique id
//audioid is what ties the comment to the page.

//username is taken from the usersession
//comment is taken from the $_POST
//I'm going to take a break. if I can finish this today, then I think that's an earned break...



// SQL is written as a String.
$query = "SELECT * FROM comments WHERE audioid = :audioid ORDER BY timestamp DESC";

// A PDO::Statement is prepared from the query.
$statement = $db->prepare($query);

// Execution on the DB server is delayed until we execute().
$statement->execute();

// Fetch all rows from the query result.
$blogposts = $statement->fetchAll(PDO::FETCH_ASSOC);

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

            <form action="audiopage.php" method="post">
                <!--  this is where the commens are goign to be handled. ez pz.-->
        
                <p>User: <?= $_SESSION['username']?></p>
                <input type="hidden" name="audioid" value="<?= $audioid?>">
    
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" rows="4" cols="50" required></textarea>
                
        
                
                <input type="submit" value="Submit">
            </form>
        </div>

        <div id="comments">

        <?php if(empty): ?> <!-- finish this when you have the logic. -->
            <div class="comment">
                <p><strong>Username:</strong> John Doe</p>
                <p><strong>Comment:</strong> This is a sample comment.</p>
                <p><strong>Date:</strong> 2025-04-03</p>
            </div>
            <!-- Repeat for each comment -->
        </div>
        
    </main>

    <footer>
        <p>&copy; 2025 My CMS. All rights reserved.</p>
    </footer>
    
</body>
</html>
