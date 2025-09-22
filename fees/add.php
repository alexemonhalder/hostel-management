<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

include('../config/db.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = $_POST['student_name'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $paid_date = $_POST['paid_date'] ?: NULL;  // If not paid yet, keep it NULL

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO fees (student_name, month, year, amount, status, paid_date) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $student_name, $month, $year, $amount, $status, $paid_date);

    if ($stmt->execute()) {
        $success = "Fee added successfully!";
    } else {
        $error = "Error adding fee: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Fee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container mt-4">
    <h2 class="mb-3">Add New Fee</h2>

    <?php if ($success) : ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error) : ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="student_name" class="form-label">Student Name</label>
        <input type="text" name="student_name" id="student_name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="month" class="form-label">Month</label>
        <input type="text" name="month" id="month" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="year" class="form-label">Year</label>
        <input type="number" name="year" id="year" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="amount" class="form-label">Amount</label>
        <input type="number" name="amount" id="amount" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-control" required>
          <option value="Paid">Paid</option>
          <option value="Unpaid">Unpaid</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="paid_date" class="form-label">Paid Date</label>
        <input type="date" name="paid_date" id="paid_date" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Add Fee</button>
    </form>
  </div>
</body>
</html>
