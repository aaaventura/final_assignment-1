<?php
function validateSessionRole($allowedRoles){

    
    if (isset($_SESSION['role'])){
        $role = filter_var($_SESSION['role'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);


        if(!in_array($role, $allowedRoles)) {
            header("Location: accessdenied.php");
            exit;
        }
    }
    else{
        header("Location: accessdenied.php");
        exit;
    }
}

?>