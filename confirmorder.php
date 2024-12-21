<?php
session_start();
include 'dbconnect.php';
if (isset($_GET['orderID']) && isset($_GET['productid']) && isset($_GET['quantity'])) {
    $id = $_GET['orderID'];
    $quant = $_GET['quantity'];
    $product = $_GET['productid'];
    $order = $_SESSION['orders'];
    $cur = $_SESSION['email'];
    $staff = $_SESSION['staff'];
    $q = "SELECT * FROM $staff where ID = '$cur'";
    $r = mysqli_query($conn , $q);
    $ro = mysqli_fetch_assoc($r);
    $desig = $ro['designation'];
    if ($desig == 'supplier' or $desig == 'Supplier'){
        $inventory = $_SESSION['inventory'];
        $check = "SELECT * FROM $order WHERE orderID = '$id'";
        $checkex = mysqli_query($conn, $check);
        $muh = mysqli_fetch_assoc($checkex);
        $st = $muh['status'];
        if ($st == 'confirmed') {
            header("location: supplier.php");
        } else {
            $updatequery = "UPDATE `$order` SET status = 'confirmed' WHERE orderID = '$id' ";
            $execution = mysqli_query($conn, $updatequery);
            if ($execution) {
                header("Location: dashboard.php");
            }
        }
    }
    else{
        echo "
            <script>
                alert('You are not the supplier');
                window.location = './supplier.php'
            </script>
        ";
    }
}