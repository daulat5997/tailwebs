<?php
session_start();
require 'db.php';

if (!isset($_SESSION['teacher_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $mysqli = getDBConnection();
    $stmt = $mysqli->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: home.php");
        exit;
    } else {
        echo "Error deleting student: " . $stmt->error;
    }
    $stmt->close();
    $mysqli->close();
}
?>
