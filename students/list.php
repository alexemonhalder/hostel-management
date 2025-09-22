<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

include('../config/db.php');

// Get filter values
$bloodGroupFilter = isset($_GET['blood_group']) ? trim($_GET['blood_group']) : '';
$educationLevelFilter = isset($_GET['education_level']) ? trim($_GET['education_level']) : '';

// Build base SQL
$sql = "SELECT * FROM students WHERE 1";

// Apply blood group filter
if ($bloodGroupFilter !== '') {
    $sql .= " AND blood_group = ?";
}

// Apply education level filter
if ($educationLevelFilter !== '') {
    $sql .= " AND education_level = ?";
}

$stmt = $conn->prepare($sql);

// Bind parameters for the prepared statement
if ($bloodGroupFilter !== '' && $educationLevelFilter !== '') {
    $stmt->bind_param("ss", $bloodGroupFilter, $educationLevelFilter);
} elseif ($bloodGroupFilter !== '') {
    $stmt->bind_param("s", $bloodGroupFilter);
} elseif ($educationLevelFilter !== '') {
    $stmt->bind_param("s", $educationLevelFilter);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Students</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container mt-4">
    <!-- Dashboard Button -->
    <a href="../public/dashboard.php" class="btn btn-primary mb-3">Back to Dashboard</a>

    <h2 class="mb-3">Student Management</h2>

    <a href="add.php" class="btn btn-success mb-3">Add New Student</a>

    <!-- Filter Form -->
    <form method="GET" class="row mb-4">
      <div class="col-md-3 mb-2">
        <select class="form-select" name="blood_group">
          <option value="">Filter by Blood Group</option>
          <option value="A+" <?= $bloodGroupFilter === 'A+' ? 'selected' : '' ?>>A+</option>
          <option value="A-" <?= $bloodGroupFilter === 'A-' ? 'selected' : '' ?>>A-</option>
          <option value="B+" <?= $bloodGroupFilter === 'B+' ? 'selected' : '' ?>>B+</option>
          <option value="B-" <?= $bloodGroupFilter === 'B-' ? 'selected' : '' ?>>B-</option>
          <option value="O+" <?= $bloodGroupFilter === 'O+' ? 'selected' : '' ?>>O+</option>
          <option value="O-" <?= $bloodGroupFilter === 'O-' ? 'selected' : '' ?>>O-</option>
          <option value="AB+" <?= $bloodGroupFilter === 'AB+' ? 'selected' : '' ?>>AB+</option>
          <option value="AB-" <?= $bloodGroupFilter === 'AB-' ? 'selected' : '' ?>>AB-</option>
        </select>
      </div>
      <div class="col-md-3 mb-2">
        <select class="form-select" name="education_level">
          <option value="">Filter by Education Level</option>
          <option value="honors" <?= $educationLevelFilter === 'honors' ? 'selected' : '' ?>>Honors</option>
          <option value="inter" <?= $educationLevelFilter === 'inter' ? 'selected' : '' ?>>Inter</option>
        </select>
      </div>
      <div class="col-md-3 mb-2">
        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
      </div>
    </form>

    <!-- Export Buttons -->
    <div class="mb-3">
      <a href="export_excel.php" class="btn btn-outline-success me-2">Export to Excel</a>
      <a href="export_pdf.php" class="btn btn-outline-danger">Export to PDF</a>
    </div>

    <table class="table table-bordered table-hover" id="studentTable">
      <thead class="table-light">
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Guardian's Phone</th>
          <th>Father's Name</th>
          <th>Mother's Name</th>
          <th>Blood Group</th>
          <th>Educational Level</th>
          <th>College/ University</th>
          <th>Mission</th>
          <th>Address</th>
          <th>Join Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
          <tr>
            <td>
              <?php if ($row['image']) : ?>
                <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="Student Image" style="width: 50px; height: 50px; object-fit: cover;">
              <?php else : ?>
                <img src="../assets/images/default.png" alt="No Image" style="width: 50px; height: 50px;">
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['student_name']) ?></td>
            <td><?= htmlspecialchars($row['phone_number']) ?></td>
            <td><?= htmlspecialchars($row['guardian_phone']) ?></td>
            <td><?= htmlspecialchars($row['fathers_name']) ?></td>
            <td><?= htmlspecialchars($row['mothers_name']) ?></td>
            <td><?= htmlspecialchars($row['blood_group']) ?></td>
            <td><?= htmlspecialchars($row['education_level']) ?></td>
            <td><?= htmlspecialchars($row['college_name']) ?></td>
            <td><?= htmlspecialchars($row['mission']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['address'])) ?></td>
            <td><?= htmlspecialchars($row['join_date']) ?></td>
            <td>
              <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <script src="../assets/js/scripts.js"></script>
</body>
</html>
