<?php
session_start();
include 'dbconnect.php';
if (isset($_GET['orderID']) & isset($_GET['productid']) & isset($_GET['quantity'])) {
    $id = $_GET['orderID'];
    $quant = $_GET['quantity'];
    $product = $_GET['productid'];
}
$cur = $_SESSION['email'];
$order = $_SESSION['orders'];
$staff = $_SESSION['staff'];
$inventory = $_SESSION['inventory'];
$extract = "SELECT * FROM `$order` WHERE orderID = '$id'";
$extractexe = mysqli_query($conn , $extract);
$stat = mysqli_fetch_assoc($extractexe);
$st = $stat['status'];
$q = "SELECT * FROM $staff where ID = '$cur'";
$r = mysqli_query($conn , $q);
$ro = mysqli_fetch_assoc($r);
$desig = $ro['designation'];
if ($desig == 'supplier' or $desig == 'Supplier'){
    echo
    "<script>
        alert('you are a supplier');
        window.location = './orders.php';
    </script>";
}
else{
if ($st == 'confirmed'){
    $updatequery = "UPDATE `$order` SET status = 'Received' WHERE orderID = '$id' ";
    $execution = mysqli_query($conn , $updatequery);
    $get = "SELECT * FROM `$inventory` WHERE productID = '$product'";
    $row = mysqli_fetch_assoc(mysqli_query($conn,$get));
    $current = $row['quantity'];
    $new = $current + $quant;
    $updatequery2 = "UPDATE `$inventory` SET quantity = '$new' WHERE productID = '$product'";
    mysqli_query($conn, $updatequery2);
    if ($execution){
        header("Location: dashboard.php");
    }
}

else{
    echo "<script>
    alert('Order not confirmed yet'); 
    window.location = 'orders.php';
    </script>";
}
}
