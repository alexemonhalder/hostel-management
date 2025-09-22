<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

include('../config/db.php');

// Get search and filter values
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? trim($_GET['status']) : '';
$monthFilter = isset($_GET['month']) ? trim($_GET['month']) : '';
$yearFilter = isset($_GET['year']) ? trim($_GET['year']) : '';

// Build base SQL
$sql = "SELECT * FROM fees WHERE student_name LIKE ?";
$params = [];
$types = "s";
$searchParam = "%" . $searchQuery . "%";
$params[] = &$searchParam;

// Apply status filter
if ($statusFilter !== '') {
    $sql .= " AND status = ?";
    $types .= "s";
    $params[] = &$statusFilter;
}

// Apply month filter
if ($monthFilter !== '') {
    $sql .= " AND month = ?";
    $types .= "s";
    $params[] = &$monthFilter;
}

// Apply year filter
if ($yearFilter !== '') {
    $sql .= " AND year = ?";
    $types .= "s";
    $params[] = &$yearFilter;
}

// Prepare and execute
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// For dropdown options
$months = [
    '', 'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];
$currentYear = date('Y');
$years = range($currentYear - 5, $currentYear + 2); // Adjust as needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Fee Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Fee Management</h2>
      <div>
        <a href="../public/dashboard.php" class="btn btn-primary me-2">Back to Dashboard</a>
        <a href="add.php" class="btn btn-success">+ Add Fee</a>
      </div>
    </div>

    <form class="row mb-4" action="list.php" method="GET">
      <div class="col-md-3 mb-2">
        <input type="text" name="search" class="form-control" placeholder="Search by student name" value="<?= htmlspecialchars($searchQuery) ?>">
      </div>

      <div class="col-md-2 mb-2">
  <select class="form-select" name="month">
    <option value="" disabled selected>All Months</option>
    <?php foreach ($months as $index => $m): ?>
      <?php if ($index > 0): ?> <!-- Skip the first empty element for the placeholder -->
        <option value="<?= $m ?>" <?= $monthFilter === $m ? 'selected' : '' ?>><?= $m ?></option>
      <?php endif; ?>
    <?php endforeach; ?>
  </select>
</div>


     <div class="col-md-2 mb-2">
  <select class="form-select" name="year">
    <option value="" disabled selected>All Years</option>
    <?php foreach ($years as $y): ?>
      <option value="<?= $y ?>" <?= $yearFilter == $y ? 'selected' : '' ?>><?= $y ?></option>
    <?php endforeach; ?>
  </select>
</div>


      <div class="col-md-2 mb-2">
        <select class="form-select" name="status">
          <option value="">All Status</option>
          <option value="Paid" <?= $statusFilter === 'Paid' ? 'selected' : '' ?>>Paid</option>
          <option value="Unpaid" <?= $statusFilter === 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
        </select>
      </div>
      <div class="col-md-3 mb-2">
        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
      </div>

      <div class="mb-3 d-flex justify-content-end gap-2">
  <a href="export_excel.php<?= $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '' ?>" class="btn btn-outline-success me-2">
    Export to Excel
  </a>
  <a href="export_pdf.php<?= $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '' ?>" class="btn btn-outline-danger">
    Export to PDF
  </a>
</div>

    </form>

    <table class="table table-bordered table-hover">
      <thead class="table-light">
        <tr>
          <th>Student Name</th>
          <th>Month</th>
          <th>Year</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Paid Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
              <td><?= htmlspecialchars($row['student_name']) ?></td>
              <td><?= htmlspecialchars($row['month']) ?></td>
              <td><?= htmlspecialchars($row['year']) ?></td>
              <td><?= htmlspecialchars($row['amount']) ?></td>
              <td><?= htmlspecialchars($row['status']) ?></td>
              <td><?= htmlspecialchars($row['paid_date']) ?></td>
              <td>
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this fee record?');">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center text-muted">No fee records found for selected filters.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <script src="../assets/js/scripts.js"></script>
</body>
</html>
