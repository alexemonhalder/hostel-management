<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

include('../config/db.php');

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Delete the student from the database
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $student_id);

    if ($stmt->execute()) {
        header('Location: list.php');
        exit();
    } else {
        echo "Error deleting student: " . $stmt->error;
    }
} else {
    header('Location: list.php');
    exit();
}
?>
