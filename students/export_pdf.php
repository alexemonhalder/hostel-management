<?php
require_once('../library/tcpdf/tcpdf.php');
include('../config/db.php');

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

$html = '<h2>Student List</h2>';
$html .= '<table border="1" cellpadding="4">
<tr>
  <th><b>Name</b></th>
  <th><b>Phone</b></th>
  <th><b>Guardian</b></th>
  <th><b>Father</b></th>
  <th><b>Mother</b></th>
  <th><b>Blood</b></th>
  <th><b>Level</b></th>
  <th><b>College</b></th>
  <th><b>Mission</b></th>
  <th><b>Address</b></th>
  <th><b>Join Date</b></th>
</tr>';

$result = $conn->query("SELECT * FROM students");
while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
        <td>{$row['student_name']}</td>
        <td>{$row['phone_number']}</td>
        <td>{$row['guardian_phone']}</td>
        <td>{$row['fathers_name']}</td>
        <td>{$row['mothers_name']}</td>
        <td>{$row['blood_group']}</td>
        <td>{$row['education_level']}</td>
        <td>{$row['college_name']}</td>
        <td>{$row['mission']}</td>
        <td>{$row['address']}</td>
        <td>{$row['join_date']}</td>
    </tr>";
}
$html .= '</table>';

$pdf->writeHTML($html);
$pdf->Output('students.pdf', 'D');
?>
