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
    $phone_number = $_POST['phone_number'];
    $fathers_name = $_POST['fathers_name'];
    $mothers_name = $_POST['mothers_name'];
    $guardian_phone = $_POST['guardian_phone'];
    $mission = $_POST['mission'];
    $address = $_POST['address'];
    $blood_group = $_POST['blood_group'];
    $education_level = $_POST['education_level'];
    $college_name = $_POST['college_name'];
    $join_date = $_POST['join_date'];

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_path = '../uploads/' . $image_name;
        move_uploaded_file($image_tmp_name, $image_path);
    } else {
        $image_name = null;
    }

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO students (student_name, phone_number, fathers_name, mothers_name, guardian_phone, mission, address, blood_group, education_level, college_name, join_date, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssss", $student_name, $phone_number, $fathers_name, $mothers_name, $guardian_phone, $mission, $address, $blood_group, $education_level, $college_name, $join_date, $image_name);

    if ($stmt->execute()) {
        $success = "Student added successfully!";
    } else {
        $error = "Error adding student: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container mt-4">
    <h2>Add New Student</h2>

    <?php if ($success) : ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error) : ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="student_name" class="form-label">Student Name</label>
        <input type="text" name="student_name" id="student_name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="phone_number" class="form-label">Phone Number</label>
        <input type="text" name="phone_number" id="phone_number" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="fathers_name" class="form-label">Father's Name</label>
        <input type="text" name="fathers_name" id="fathers_name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="mothers_name" class="form-label">Mother's Name</label>
        <input type="text" name="mothers_name" id="mothers_name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="guardian_phone" class="form-label">Guardian's Phone</label>
        <input type="text" name="guardian_phone" id="guardian_phone" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="mission" class="form-label">Mission</label>
        <input type="text" name="mission" id="mission" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea name="address" id="address" class="form-control" required></textarea>
      </div>

      <!-- Blood Group Dropdown -->
      <div class="mb-3">
        <label for="blood_group" class="form-label">Blood Group</label>
        <select name="blood_group" id="blood_group" class="form-select" required>
          <option value="">Select Blood Group</option>
          <option value="A+" <?= isset($blood_group) && $blood_group == 'A+' ? 'selected' : '' ?>>A+</option>
          <option value="A-" <?= isset($blood_group) && $blood_group == 'A-' ? 'selected' : '' ?>>A-</option>
          <option value="B+" <?= isset($blood_group) && $blood_group == 'B+' ? 'selected' : '' ?>>B+</option>
          <option value="B-" <?= isset($blood_group) && $blood_group == 'B-' ? 'selected' : '' ?>>B-</option>
          <option value="O+" <?= isset($blood_group) && $blood_group == 'O+' ? 'selected' : '' ?>>O+</option>
          <option value="O-" <?= isset($blood_group) && $blood_group == 'O-' ? 'selected' : '' ?>>O-</option>
          <option value="AB+" <?= isset($blood_group) && $blood_group == 'AB+' ? 'selected' : '' ?>>AB+</option>
          <option value="AB-" <?= isset($blood_group) && $blood_group == 'AB-' ? 'selected' : '' ?>>AB-</option>
        </select>
      </div>

      <!-- Educational Level Dropdown -->
      <div class="mb-3">
        <label for="education_level" class="form-label">Education Level</label>
        <select name="education_level" id="education_level" class="form-select" required>
          <option value="">Select Education Level</option>
          <option value="Honors" <?= isset($education_level) && $education_level == 'honors' ? 'selected' : '' ?>>Honors</option>
          <option value="Inter" <?= isset($education_level) && $education_level == 'inter' ? 'selected' : '' ?>>Inter</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="college_name" class="form-label">College/University Name</label>
        <input type="text" name="college_name" id="college_name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="join_date" class="form-label">Join Date</label>
        <input type="date" name="join_date" id="join_date" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="image" class="form-label">Student Image</label>
        <input type="file" name="image" id="image" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Add Student</button>
    </form>
  </div>
</body>
</html>
