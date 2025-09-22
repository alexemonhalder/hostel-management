<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

include('../config/db.php');

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Fetch student data
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        header('Location: list.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container mt-4">
    <h2 class="mb-3">Student Details</h2>

    <table class="table table-bordered">
      <tr>
        <th>Name</th>
        <td><?= htmlspecialchars($student['student_name']) ?></td>
      </tr>
      <tr>
        <th>Phone</th>
        <td><?= htmlspecialchars($student['phone_number']) ?></td>
      </tr>
      <!-- Add other student fields -->
    </table>

    <a href="list.php" class="btn btn-secondary">Back to List</a>
  </div>
</body>
</html>
