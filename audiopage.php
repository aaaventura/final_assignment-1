<?php

/*******w******** 
    
    Name:Ahleeryan-Joe Ventura
    Date:2025-03-31
    Description: Index for first page assignment

****************/
session_start();
require('connect.php');





// verifying user
$allowedRoles = ['admin', 'artist', 'employee', 'browser'];

require('validaterole.php');
validateSessionRole($allowedRoles);




if ($_SERVER['REQUEST_METHOD'] == 'GET'){

    $audioId = $_GET['id'];

    $errors = [];

    if(!is_numeric($audioId)){
        $errors[] = "id must be a number";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: invalidinput.php");
        exit;
    }
    else{

        //continue with regular logic
        // fetching data from specific row.
        $query = "SELECT * FROM audio WHERE id = :id";
        $statement = $db -> prepare($query);
        $statement -> bindValue( ':id', $audioId, PDO::PARAM_INT);
        $statement->execute();
        $audioData = $statement -> fetch(PDO::FETCH_ASSOC);

        
        // printing comments from GET
        $query = "SELECT * FROM comments WHERE audioid = :audioid ORDER BY timestamp DESC";

        $statement = $db->prepare($query);

        $statement -> bindValue(':audioid', $audioId);

        $statement->execute();

        $commentposts = $statement->fetchAll(PDO::FETCH_ASSOC);

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






?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Homepage</title>
    <script src="captcha.js"></script>
</head>
<body>

    <?php include 'header.php' ?>


    <main>
    

        <div>
            <div id="form-container">
                <audio controls>
                            <source src="<?=$audioData['fileLocation'] ?>" type="<?php fileExtension($audioData['fileLocation'])?>">
                            your browser does not support the audio element
                        </audio>
                <div>
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
                    
                </div>
            </div>
        </div>


        <div id="comments-section">

            <form action="postcomment.php" method="post">
        
                <p>User: <?= $_SESSION['username']?></p>
                <input type="hidden" name="audioid" value="<?= $audioData['id'] ?>">
    
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" rows="4" cols="50" required></textarea>
                
                <canvas id="captcha"></canvas>
                <input id="textBox" type="text" name="textBox" required>

                <button type="submit">Post Comment</button>
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
                    <p><a href="deletecomment.php?commentid=<?= $comment['commentid']?>">delete</a></p>

                <?php endif; ?>
            

            <?php endforeach; ?>

        <?php endif; ?>
        </div>

        
        
    </main>

    <?php include 'footer.php' ?>
    
</body>
</html>
