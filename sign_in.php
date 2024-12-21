<?php
session_start();
if (isset($_SESSION['loggedin'])) {
    header('location: dashboard.php');
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnect.php';

    // Get the input from the form
    $num = $_POST['number'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Check if store exists
    $check = "SELECT * FROM `StoreNum` WHERE `StoreNum`= '$num'";
    $result = mysqli_query($conn, $check);
    $numExistRows = mysqli_num_rows($result);
    $_SESSION['email'] = $email;
    if ($numExistRows > 0) {
        // Store exists, proceed to check user
        $storenum = "store".strval($num);
        $query1 = "SELECT * FROM $storenum WHERE `emailID` = '$email';";
        $result1 = mysqli_query($conn, $query1);
        $_SESSION['storenum'] = $storenum;
        $inventory = "inventory".strval($num);
        $orders = "orders".strval($num);
        $bill = "bill".strval($num);                   
        $staff = "staff".strval($num);
        $warehouse = "warehouse".strval($num);
        $_SESSION['number'] = $num;
        $_SESSION['email'] = $email;
        $_SESSION['inventory'] = $inventory;
        $_SESSION['orders'] = $orders;
        $_SESSION['bill'] = $bill;
        $_SESSION['staff'] = $staff;
        $_SESSION['warehouse'] = $warehouse;
        $_SESSION['store'] = $storenum;
        if (mysqli_num_rows($result1) > 0) {
            // Fetch the user data
            $row = mysqli_fetch_assoc($result1);

            // Verify the password
            if (password_verify($password, $row['userpassword'])) {
                $_SESSION['username'] = strtoupper($row['username']);
                session_start();
                $_SESSION['loggedin'] = true;
                // Password is correct, redirect to dashboard
                header("Location: dashboard.php");
                exit;
            } else {
                // Incorrect password
                echo "<script>alert('Incorrect password. Please try again.');</script>";
            }
        } else {
            // Email not found in store
            echo "<script>alert('Email not found in this store. Please check your credentials.');</script>";
        }
    } else {
        // Store number not found
        echo "<script>alert('Store number does not exist. Please try again.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sign_in.css">
    <title>Sign In</title>
</head>
<body>
    <div class="container">
        <div class="logo_block">
            <img src="src/logo.png" class="logo" alt="Logo">
        </div>
        <div class="form">
            <h3>Welcome back!!</h3>
            <h2>Please Sign In</h2>
            <form method = "post" action = "">
                <label for="email">Email address</label>
                <input type="email" id="email" placeholder="Enter email address" name="email" required>
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="*********" name="password" required>
                <label for="store-number">Store number</label>
                <input type="number" id="number" name="number" required>
                <div class="remember">
                    <a href="forgot.php" class="forgot-password">forgot password?</a>
                    <a href="contact_us.php" class="contact">Don't have an account?</a>
                </div>
                <button type="submit" class="signin-button">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>