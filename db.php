<?php
function getDBConnection() {
    $host = 'localhost';
    $db = 'teacher_portal';
    $user = 'root';  
    $pass = ''; 

    $mysqli = new mysqli($host, $user, $pass, $db);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}
?>
