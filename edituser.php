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

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); 
    exit;
}






// get id
$userId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
// If $id is not an INT, return to index.php
if(!$userId){
    //header("Location: index.php");
    echo "not correct value in get";
}


// fetching data from specific row.
$query = "SELECT * FROM users WHERE id = :id";
$statement = $db -> prepare($query);
$statement -> bindValue( ':id', $userId, PDO::PARAM_INT);
$statement->execute();
$userData = $statement -> fetch(PDO::FETCH_ASSOC);


// for roles
$defaultChoice = htmlspecialchars_decode($userData['role']);




// after post. either delete or edit.


// _POST all relevant data for processes.
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$nameUser = filter_input(INPUT_POST, 'nameUser', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$command = filter_input(INPUT_POST,'command', FILTER_SANITIZE_STRING);


// Checks for Update command.

//extra todo: make sure this doesn't activate when first time--only activate when a post command is given.
// it is making unknown show up after the "Logged in as: admin"
if($command === 'Update'){
    
    // Updates specific row based on id.
    $query     = "UPDATE users SET name = :nameUser, username = :username, password = :password, role = :role WHERE id = :id";

   
    
    $statement = $db->prepare($query);

    $statement->bindValue(':nameUser', $nameUser);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);
    $statement->bindValue(':role', $role);
    $statement->bindValue(':id', $id);

   
    try {
        if ($statement->execute()) {
            echo "Update successful!";
        } else {
            echo "Update failed!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    
    

    // Return to index when complete.
    header("Location: adminpage.php");
}






// Elseif Delete command. 
elseif($command =='Delete'){

    // Deletes from specific row based on id.
    $query = "DELETE FROM users WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();

    // Return to index when complete.
    header("Location: adminpage.php");
}

else{
    echo "unknown";
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
                <p>Edit audio file data</p>

                <form action="#" method="post">
                <label for="name">edit current user</label>
                <input type="text" id="nameUser" name="nameUser" value="<?= htmlspecialchars_decode($userData['name']); ?>"> 
                <input type="text" id="username" name="username" value="<?= htmlspecialchars_decode($userData['username']); ?>"> 
                <input type="text" id="password" name="password" value="<?= htmlspecialchars_decode($userData['password']); ?>"> 
                <select id="role" name="role">
                    <option value="admin" <?php echo ($defaultChoice == "admin") ? "selected" : "";?>>  Admin</option>
                    <option value="employee" <?php echo ($defaultChoice == "employee") ? "selected" : "";?>>Employee</option>
                    <option value="artist" <?php echo ($defaultChoice == "artist") ? "selected" : "";?>>Artist</option>
                </select>
                <input type="hidden" name="id" value="<?= $userData['id']?>">
                <div>
                        <input type="submit" name="command" value="Update">
                        <input type="submit" name="command" value="Delete">
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 My CMS. All rights reserved.</p>
    </footer>
    
</body>
</html>
