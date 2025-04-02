<?php
session_start();
require('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    var_dump($user);

    // Verify password
    if ($user && $password === $user['password']) {
        // Store user data in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Assuming you have a 'role' column

        header("Location: index.php"); // Redirect to dashboard
        exit;
    } else {
        echo "<p>Invalid login credentials. Please try again.</p>";
        
    }
}

?>


