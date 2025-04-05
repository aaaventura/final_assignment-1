<?php 

if (isset($_SESSION['role'])) {
    $role = filter_var($_SESSION['role'], FILTER_SANITIZE_STRING);
    
    if (!is_string($role) || empty($role) || $role !== 'admin') {
        header("Location: accessdenied.php");
        exit;
    }

} else {
    header("Location: accessdenied.php");
    exit;
}

?>