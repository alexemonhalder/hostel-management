<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

include('../config/db.php');

if (isset($_GET['id'])) {
    $fee_id = $_GET['id'];

    // Fetch fee data
    $stmt = $conn->prepare("SELECT * FROM fees WHERE id = ?");
    $stmt->bind_param("i", $fee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $fee = $result->fetch_assoc();

    if (!$fee) {
        header('Location: list.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Fee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container mt-4">
    <h2 class="mb-3">Fee Details</h2>

    <table class="table table-bordered">
      <tr>
        <th>Student Name</th>
        <td><?= htmlspecialchars($fee['student_name']) ?></td>
      </tr>
      <tr>
        <th>Month</th>
        <td><?= htmlspecialchars($fee['month']) ?></td>
      </tr>
      <tr>
        <th>Year</th>
        <td><?= htmlspecialchars($fee['year']) ?></td>
      </tr>
      <tr>
        <th>Amount</th>
        <td><?= htmlspecialchars($fee['amount']) ?></td>
      </tr>
      <tr>
        <th>Status</th>
        <td><?= htmlspecialchars($fee['status']) ?></td>
      </tr>
      <tr>
        <th>Paid Date</th>
        <td><?= htmlspecialchars($fee['paid_date']) ?></td>
      </tr>
    </table>

    <a href="list.php" class="btn btn-secondary">Back to List</a>
  </div>
</body>
</html>
