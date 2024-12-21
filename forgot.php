<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnect.php';

    // Get the input from the form
    $num = $_POST['number'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $confirm = $_POST['confirm'];

    // Check if store exists
    $check = "SELECT * FROM `stores` WHERE `StoreNum`= '$num'";
    $result = mysqli_query($conn, $check);
    $numExistRows = mysqli_num_rows($result);

    if ($numExistRows > 0) {
        // Store exists, proceed to check user
        $storenum = "store".strval($num);
        $query1 = "SELECT * FROM `$storenum` WHERE `emailID` = '$email';";
        $result1 = mysqli_query($conn, $query1);
        $resultnum = mysqli_num_rows($result1);
        if ($resultnum > 0){
            if ($password == $confirm){
            $hash = password_hash($password , PASSWORD_DEFAULT);
            $existinsert = "UPDATE `$storenum` set `userpassword` = '$hash' where `emailID` = '$email'";
            $resultupdate = mysqli_query($conn , $existinsert);
            header("Location: sign_in.php");
        }
        else{
            echo"<script>alert('password and confirm password donot match')</script>";
            header("Location: forgot.php");
        }}}
    else{
        echo"<script>alert('store number doesn't exist')</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/forgot.css">
    <title>Sign In</title>
</head>
<body>
    <div class="container">
        <div class="logo_block">
            <img src="src/logo.png" class="logo" alt="Logo">
        </div>
        <div class="form">
            <h5>Please fill the details to change password</h5>
            <form method = "post" action = "">
                <label for="email">Email address</label>
                <input type="email" id="email" placeholder="Enter email address" name="email" required>
                <label for="store-number">Store number</label>
                <input type="number" id="number" name="number" required>
                <label for="password">New Password</label>
                <input type="password" id="password" placeholder="new password" name="password" required>
                <label for="password">Confirm new password</label>
                <input type="password" id="password" placeholder="confirm new password" name="confirm" required>
                <a href="./sign_in.php">back to sign in</a>
                <button type="submit" class="signin-button">change password</button>
            </form>
        </div>
    </div>
</body>
</html>