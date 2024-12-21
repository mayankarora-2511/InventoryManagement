<?php
session_start();
include 'dbconnect.php';

$order = $_SESSION['orders']; 
$sql = "SELECT * FROM $order ORDER BY ordervalue";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error retrieving data: " . mysqli_error($conn));
}
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="orders_export.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Order ID', 'Product ID' , 'Quantity' , 'Status' , 'Order Value']);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);

$conn->close();
?>
