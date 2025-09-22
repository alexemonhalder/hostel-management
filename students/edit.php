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
    $student_id = $_GET['id'];

    // Fetch existing student data
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        header('Location: list.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $student_name     = $_POST['student_name'];
        $phone_number     = $_POST['phone_number'];
        $fathers_name     = $_POST['fathers_name'];
        $mothers_name     = $_POST['mothers_name'];
        $guardian_phone   = $_POST['guardian_phone'];
        $mission          = $_POST['mission'];
        $address          = $_POST['address'];
        $blood_group      = $_POST['blood_group'];
        $education_level  = $_POST['education_level'];
        $college_name     = $_POST['college_name'];
        $join_date        = $_POST['join_date'];

        $image = $_FILES['image']['name'];
        if ($image) {
            $imagePath = '../uploads/' . basename($image);
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        } else {
            $image = $student['image']; // Use old image if new one is not uploaded
        }

        $stmt = $conn->prepare("UPDATE students SET student_name = ?, phone_number = ?, fathers_name = ?, mothers_name = ?, guardian_phone = ?, mission = ?, address = ?, blood_group = ?, education_level = ?, college_name = ?, join_date = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssssssssssssi", $student_name, $phone_number, $fathers_name, $mothers_name, $guardian_phone, $mission, $address, $blood_group, $education_level, $college_name, $join_date, $image, $student_id);

        if ($stmt->execute()) {
            $success = "Student details updated successfully!";
        } else {
            $error = "Error updating student: " . $stmt->error;
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
  <title>Edit Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container mt-4">
     <!-- Dashboard Button -->
    <a href="../public/dashboard.php" class="btn btn-primary mb-3">Back to Dashboard</a>
    <h2 class="mb-3">Edit Student</h2>

    <?php if ($success) : ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error) : ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="row">
        <div class="mb-3 col-md-6">
          <label for="student_name" class="form-label">Student Name</label>
          <input type="text" name="student_name" id="student_name" class="form-control" value="<?= htmlspecialchars($student['student_name']) ?>" required>
        </div>
        <div class="mb-3 col-md-6">
          <label for="phone_number" class="form-label">Phone Number</label>
          <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?= htmlspecialchars($student['phone_number']) ?>" required>
        </div>
        <div class="mb-3 col-md-6">
          <label for="fathers_name" class="form-label">Father's Name</label>
          <input type="text" name="fathers_name" id="fathers_name" class="form-control" value="<?= htmlspecialchars($student['fathers_name']) ?>">
        </div>
        <div class="mb-3 col-md-6">
          <label for="mothers_name" class="form-label">Mother's Name</label>
          <input type="text" name="mothers_name" id="mothers_name" class="form-control" value="<?= htmlspecialchars($student['mothers_name']) ?>">
        </div>
        <div class="mb-3 col-md-6">
          <label for="guardian_phone" class="form-label">Guardian Phone</label>
          <input type="text" name="guardian_phone" id="guardian_phone" class="form-control" value="<?= htmlspecialchars($student['guardian_phone']) ?>">
        </div>
        <div class="mb-3 col-md-6">
          <label for="mission" class="form-label">Mission</label>
          <input type="text" name="mission" id="mission" class="form-control" value="<?= htmlspecialchars($student['mission']) ?>">
        </div>
        <div class="mb-3 col-md-12">
          <label for="address" class="form-label">Address</label>
          <textarea name="address" id="address" class="form-control" rows="2"><?= htmlspecialchars($student['address']) ?></textarea>
        </div>
        <div class="mb-3 col-md-6">
          <label for="blood_group" class="form-label">Blood Group</label>
          <input type="text" name="blood_group" id="blood_group" class="form-control" value="<?= htmlspecialchars($student['blood_group']) ?>">
        </div>
        <div class="mb-3 col-md-6">
          <label for="education_level" class="form-label">Educational Level</label>
          <input type="text" name="education_level" id="education_level" class="form-control" value="<?= htmlspecialchars($student['education_level']) ?>">
        </div>
        <div class="mb-3 col-md-6">
          <label for="college_name" class="form-label">College/University</label>
          <input type="text" name="college_name" id="college_name" class="form-control" value="<?= htmlspecialchars($student['college_name']) ?>">
        </div>
        <div class="mb-3 col-md-6">
          <label for="join_date" class="form-label">Join Date</label>
          <input type="date" name="join_date" id="join_date" class="form-control" value="<?= htmlspecialchars($student['join_date']) ?>">
        </div>
        <div class="mb-3 col-md-6">
          <label for="image" class="form-label">Student Image</label>
          <input type="file" name="image" id="image" class="form-control">
          <?php if ($student['image']) : ?>
            <img src="../uploads/<?= htmlspecialchars($student['image']) ?>" class="mt-2" width="80">
          <?php endif; ?>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Update Student</button>
      <a href="list.php" class="btn btn-secondary">Back to List</a>
    </form>
  </div>
</body>
</html>
