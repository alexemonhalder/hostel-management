<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header('Location: login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Don Bosco Hostel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- Custom Styles -->
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
      min-height: 100vh;
      background: linear-gradient(135deg, #007bff, #6610f2);
      color: white;
      padding-top: 40px;
    }

    .sidebar .nav-link {
      color: #fff;
      margin-bottom: 15px;
      font-size: 16px;
      font-weight: 500;
    }

    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
      background: rgba(255,255,255,0.2);
      border-radius: 10px;
    }

    .dashboard-title {
      font-weight: 700;
      font-size: 28px;
      color: #343a40;
    }

    .welcome-box {
      background: #ffffff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.05);
      margin-bottom: 30px;
    }

    .card-tile {
      background: linear-gradient(145deg, #ffffff, #e3e8ef);
      border: none;
      border-radius: 15px;
      padding: 30px;
      text-align: center;
      transition: all 0.3s ease-in-out;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }

    .card-tile:hover {
      transform: translateY(-8px);
      box-shadow: 0 16px 40px rgba(0,0,0,0.1);
    }

    .card-icon {
      font-size: 48px;
      color: #0d6efd;
      margin-bottom: 15px;
    }

    .card-title {
      font-size: 20px;
      font-weight: 600;
    }

    .logout-link {
      color: #ffc107 !important;
      margin-top: 50px;
      font-weight: 600;
    }

    .footer {
      background: #343a40;
      color: #fff;
      padding: 30px;
      text-align: center;
      margin-top: 50px;
      border-radius: 15px;
    }

    .footer h4 {
      font-weight: 700;
    }

    .footer p {
      font-size: 16px;
    }

    .footer a {
      color: #0d6efd;
      text-decoration: none;
    }

    .footer a:hover {
      color: #ffc107;
    }

    @media (max-width: 768px) {
      .sidebar {
        min-height: auto;
        padding: 20px;
      }
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 sidebar">
      <h4 class="text-center mb-4"><i class="bi bi-building-fill me-2"></i>Admin Panel</h4>
      <ul class="nav flex-column px-3">
        <li class="nav-item">
          <a class="nav-link active" href="#"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../students/list.php"><i class="bi bi-people-fill me-2"></i>Students</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../fees/list.php"><i class="bi bi-cash-coin me-2"></i>Fees</a>
        </li>
        <li class="nav-item mt-4">
          <a class="nav-link logout-link" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
        </li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 p-5">
      <div class="welcome-box">
        <h2 class="dashboard-title">Welcome to Don Bosco Students' Home Management System</h2>
        <p class="text-muted">Manage student information and fee records from the panel below. Stay organized and efficient.</p>
      </div>

      <div class="row g-4">
        <div class="col-md-6">
          <a href="../students/list.php" class="text-decoration-none">
            <div class="card-tile">
              <div class="card-icon"><i class="bi bi-person-badge-fill"></i></div>
              <div class="card-title">Student Management</div>
              <p class="text-muted">Add, update, view or delete student data.</p>
            </div>
          </a>
        </div>
        <div class="col-md-6">
          <a href="../fees/list.php" class="text-decoration-none">
            <div class="card-tile">
              <div class="card-icon"><i class="bi bi-wallet2"></i></div>
              <div class="card-title">Fee Management</div>
              <p class="text-muted">Track monthly fees and payments easily.</p>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  <p>Turning ideas into reality - made by <strong>Alex Emon Halder</strong>.</p>
</div>

</body>
</html>
