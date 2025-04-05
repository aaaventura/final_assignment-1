<?php
if (isset($_SESSION['name'])) {

$name = $_SESSION['name'];
//$role = $_SESSION['role'];

if(!is_string($name) || empty($name)){
    echo "Invalid session";
    exit;
}

$name = filter_var($name, FILTER_SANITIZE_STRING);

echo "Logged in as: " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
//echo "role: " . $role;


} else {
echo "Not logged in.";

}


?>