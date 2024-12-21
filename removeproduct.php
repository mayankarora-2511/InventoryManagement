<?php
session_start();
include 'dbconnect.php';
if (isset($_GET['productID'])) {
    $id = $_GET['productID'];
}
$inventory = $_SESSION['inventory'];
$warehouse = $_SESSION['warehouse'];
$p = "SELECT product FROM `$inventory` WHERE productid = '$id'";
$s = mysqli_fetch_assoc(mysqli_query($conn,$p));
$col = "SELECT product FROM `$inventory` WHERE `productID` = '$id' ";
$ex = mysqli_query($conn , $col);
$fet = mysqli_fetch_assoc($ex);
$name = $fet['product'];
$deletequery = "DELETE FROM `$inventory` WHERE `productID` = '$id' ";
$deletequery2 = "DELETE FROM `$warehouse` WHERE `product` = '$name' ";
$execution = mysqli_query($conn , $deletequery);
$execution2 = mysqli_query($conn , $deletequery2);
if ($execution & $execution2){
    header("Location: dashboard.php");
}
?>