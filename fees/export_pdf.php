<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../library/tcpdf/tcpdf.php';
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

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$html = '<h2>Fees List</h2><table border="1" cellpadding="4">
<tr><th><b>Student Name</b></th><th><b>Month</b></th><th><b>Year</b></th><th><b>Amount</b></th><th><b>Status</b></th><th><b>Paid Date</b></th></tr>';

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($row['student_name']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['month']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['year']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['amount']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['status']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['paid_date']) . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('fees_export.pdf', 'D');
exit;
