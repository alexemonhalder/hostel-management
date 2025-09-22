<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

include('../config/db.php');

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? trim($_GET['status']) : '';
$monthFilter = isset($_GET['month']) ? trim($_GET['month']) : '';
$yearFilter = isset($_GET['year']) ? trim($_GET['year']) : '';

$sql = "SELECT * FROM fees WHERE student_name LIKE ?";
$params = [];
$types = "s";
$searchParam = "%" . $searchQuery . "%";
$params[] = &$searchParam;

if ($statusFilter !== '') {
    $sql .= " AND status = ?";
    $types .= "s";
    $params[] = &$statusFilter;
}
if ($monthFilter !== '') {
    $sql .= " AND month = ?";
    $types .= "s";
    $params[] = &$monthFilter;
}
if ($yearFilter !== '') {
    $sql .= " AND year = ?";
    $types .= "s";
    $params[] = &$yearFilter;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=fees_export.xls");

echo "Student Name\tMonth\tYear\tAmount\tStatus\tPaid Date\n";
while ($row = $result->fetch_assoc()) {
    echo "{$row['student_name']}\t{$row['month']}\t{$row['year']}\t{$row['amount']}\t{$row['status']}\t{$row['paid_date']}\n";
}
exit;
