<?php
session_start();
require 'db.php';

if (!isset($_SESSION['teacher_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $marks = $_POST['marks'];

    $mysqli = getDBConnection();
    $stmt = $mysqli->prepare("UPDATE students SET name = ?, subject = ?, marks = ? WHERE id = ?");
    $stmt->bind_param("ssii", $name, $subject, $marks, $id);

    if ($stmt->execute()) {
        header("Location: home.php");
        exit;
    } else {
        echo "Error updating student: " . $stmt->error;
    }
    $stmt->close();
    $mysqli->close();
}
?>
