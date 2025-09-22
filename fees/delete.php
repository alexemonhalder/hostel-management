<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

include('../config/db.php');

if (isset($_GET['id'])) {
    $fee_id = $_GET['id'];

    // Delete fee from the database
    $stmt = $conn->prepare("DELETE FROM fees WHERE id = ?");
    $stmt->bind_param("i", $fee_id);

    if ($stmt->execute()) {
        header('Location: list.php');
        exit();
    } else {
        echo "Error deleting fee: " . $stmt->error;
    }
} else {
    header('Location: list.php');
    exit();
}
?>
