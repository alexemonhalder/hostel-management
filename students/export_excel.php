<?php
include('../config/db.php');

// Set headers to force download Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=students.xls");
header("Pragma: no-cache");
header("Expires: 0");

$result = $conn->query("SELECT * FROM students");

echo "<table border='1'>";
echo "<tr>
        <th>Name</th><th>Phone</th><th>Guardian Phone</th><th>Father</th>
        <th>Mother</th><th>Blood Group</th><th>Level</th><th>College</th>
        <th>Mission</th><th>Address</th><th>Join Date</th>
      </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
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
echo "</table>";
?>
