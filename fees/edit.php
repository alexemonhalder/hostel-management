<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

include('../config/db.php');

$error = '';
$success = '';

if (isset($_GET['id'])) {
    $fee_id = $_GET['id'];

    // Fetch existing fee data
    $stmt = $conn->prepare("SELECT * FROM fees WHERE id = ?");
    $stmt->bind_param("i", $fee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $fee = $result->fetch_assoc();

    if (!$fee) {
        header('Location: list.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $student_name = $_POST['student_name'];
        $month = $_POST['month'];
        $year = $_POST['year'];
        $amount = $_POST['amount'];
        $status = $_POST['status'];
        $paid_date = $_POST['paid_date'] ?: NULL;

        $stmt = $conn->prepare("UPDATE fees SET student_name = ?, month = ?, year = ?, amount = ?, status = ?, paid_date = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $student_name, $month, $year, $amount, $status, $paid_date, $fee_id);

        if ($stmt->execute()) {
            $success = "Fee details updated successfully!";
        } else {
            $error = "Error updating fee: " . $stmt->error;
        }
    }
} else {
    header('Location: list.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Fee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container mt-4">
     <!-- Dashboard Button -->
    <a href="../public/dashboard.php" class="btn btn-primary mb-3">Back to Dashboard</a>
    <h2 class="mb-3">Edit Fee</h2>

    <?php if ($success) : ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error) : ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="student_name" class="form-label">Student Name</label>
        <input type="text" name="student_name" id="student_name" class="form-control" value="<?= htmlspecialchars($fee['student_name']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="month" class="form-label">Month</label>
        <input type="text" name="month" id="month" class="form-control" value="<?= htmlspecialchars($fee['month']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="year" class="form-label">Year</label>
        <input type="number" name="year" id="year" class="form-control" value="<?= htmlspecialchars($fee['year']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="amount" class="form-label">Amount</label>
        <input type="number" name="amount" id="amount" class="form-control" value="<?= htmlspecialchars($fee['amount']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-control" required>
          <option value="Paid" <?= $fee['status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
          <option value="Unpaid" <?= $fee['status'] == 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="paid_date" class="form-label">Paid Date</label>
        <input type="date" name="paid_date" id="paid_date" class="form-control" value="<?= htmlspecialchars($fee['paid_date']) ?>">
      </div>
      <button type="submit" class="btn btn-primary">Update Fee</button>
    </form>
  </div>
</body>
</html>
