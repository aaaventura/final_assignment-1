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

// todo styles for everything now? finally?

// validate get

if ($_SERVER['REQUEST_METHOD'] == 'GET'){

    $userId = $_GET['id'];
    $errors = [];

    if (!is_numeric($userId)){
        $errors[] = "id must be a number";
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: invalidinput.php");
        exit;
    }
    else{

        $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);


        
        // fetching data from specific row.
        $query = "SELECT * FROM users WHERE id = :id";
        $statement = $db -> prepare($query);
        $statement -> bindValue( ':id', $userId, PDO::PARAM_INT);
        $statement->execute();
        $userData = $statement -> fetch(PDO::FETCH_ASSOC);


        // for roles
        $defaultChoice = htmlspecialchars_decode($userData['role']);

    }
}





// after post. either delete or edit.


if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    // gather data
    $id = $_POST['id'];
    $nameUser = $_POST['nameUser'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $command = $_POST['command'];

    

    $allowedRoles = ['admin', 'artist', 'employee', 'browser'];
    $allowedCommands = ['Update', 'Delete'];

    // validate
    if(!is_numeric($id)){
        $errors[] = "id must be a number";
    }

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

    if (!in_array($command, $allowedCommands)){
        $errors[] = "Invalid command.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: invalidinput.php");
        exit;
    }
    else{
        // continue regular logic
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $nameUser = filter_var($nameUser, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $username = filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $role = filter_var($role, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $command = filter_var($command, FILTER_SANITIZE_STRING);


        // salting and hashing password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);


        // update or delete
                
        if($command === 'Update'){
            
            // Updates specific row based on id.
            $query = "UPDATE users SET name = :nameUser, username = :username, password = :password, role = :role WHERE id = :id";

        
            
            $statement = $db->prepare($query);

            $statement->bindValue(':nameUser', $nameUser);
            $statement->bindValue(':username', $username);
            $statement->bindValue(':password', $hashedPassword);
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

            
            //echo "confirm update";
            // Return to index when complete.
            header("Location: adminpage.php");
        }


        // Elseif Delete command. 
        elseif($command === 'Delete'){
            //echo "confirm delete";

            // Deletes from specific row based on id.
            $query = "DELETE FROM users WHERE id = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            // Return to index when complete.
            header("Location: adminpage.php");
        }

        }
}





// Checks for Update command.






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
    
        <form id="form-upload" action="#" method="post">
        
            <h2>edit current user</h2>
            <input type="text" id="nameUser" name="nameUser" value="<?= htmlspecialchars_decode($userData['name']); ?>" required> 
            <input type="text" id="username" name="username" value="<?= htmlspecialchars_decode($userData['username']); ?>" required> 
            <input type="text" id="password" name="password" value="" required> 
            <select id="role" name="role">
                <option value="admin" <?php echo ($defaultChoice == "admin") ? "selected" : "";?>>  Admin</option>
                <option value="employee" <?php echo ($defaultChoice == "employee") ? "selected" : "";?>>Employee</option>
                <option value="artist" <?php echo ($defaultChoice == "artist") ? "selected" : "";?>>Artist</option>
                <option value="browser" <?php echo ($defaultChoice == "browser") ? "selected" : "";?>>Browser</option>
            </select>
            <input type="hidden" name="id" value="<?= $userData['id']?>">
            <div>
                <input class="submit-button" type="submit" name="command" value="Update">
                <input class="submit-button" type="submit" name="command" value="Delete">
            </div>
        </form>
           
    </main>

    <?php include 'footer.php' ?>
    </div>
</body>
</html>
