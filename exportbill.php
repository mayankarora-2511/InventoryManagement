<?php
session_start();
include 'dbconnect.php';

$bill = $_SESSION['bill']; 
$sql = "SELECT DATE(billdate) as bill_date, SUM(billvalue) as total_amount FROM $bill GROUP BY bill_date ORDER BY bill_date";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error retrieving data: " . mysqli_error($conn));
}
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="bills_export.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Bill Date', 'Total Amount']);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);

$conn->close();
?>
